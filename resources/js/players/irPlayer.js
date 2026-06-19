export default function irPlayer(irUrl, samplePaths) {
    return {
        playing: false,
        loading: false,
        bypass: false,
        errorMsg: null,
        progress: 0,
        currentTime: 0,
        duration: 0,
        currentTrack: 'clean',
        
        audioContext: null,
        sourceNode: null,
        convolverNode: null,
        dryGainNode: null,
        wetGainNode: null,
        analyserNode: null,
        visualizerAnimationFrame: null,
        
        irBuffer: null,
        trackBuffers: {},
        
        animationFrame: null,
        startTime: 0,
        pausedAt: 0,
        
        tracks: samplePaths,
        
        async init() {
            // Initialize Web Audio API on first interaction to respect browser autoplay policies
        },
        
        async setupAudio() {
            if (this.audioContext) return;
            this.loading = true;
            
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                this.audioContext = new AudioContext();
                
                // Fetch and decode IR
                const irResponse = await fetch(irUrl);
                if (!irResponse.ok) throw new Error('IR file not found');
                const irArrayBuffer = await irResponse.arrayBuffer();
                this.irBuffer = await this.audioContext.decodeAudioData(irArrayBuffer);
                
                // Create nodes
                this.convolverNode = this.audioContext.createConvolver();
                this.convolverNode.buffer = this.irBuffer;
                
                this.dryGainNode = this.audioContext.createGain();
                this.wetGainNode = this.audioContext.createGain();
                
                this.analyserNode = this.audioContext.createAnalyser();
                this.analyserNode.fftSize = 2048;
                
                // Initial routing (IR Active)
                this.dryGainNode.gain.value = 0;
                this.wetGainNode.gain.value = 1;
                
                // Connect nodes to destination through analyser
                this.dryGainNode.connect(this.analyserNode);
                this.convolverNode.connect(this.wetGainNode);
                this.wetGainNode.connect(this.analyserNode);
                
                this.analyserNode.connect(this.audioContext.destination);
                
                // Load current track
                await this.loadTrack(this.currentTrack);
                
            } catch (e) {
                console.error("Audio Setup Error:", e);
                this.errorMsg = "Failed to load IR. File might be invalid or corrupted.";
                this.audioContext = null;
            } finally {
                this.loading = false;
            }
        },
        
        async loadTrack(trackName) {
            if (this.trackBuffers[trackName]) return;
            
            const response = await fetch(this.tracks[trackName]);
            const arrayBuffer = await response.arrayBuffer();
            this.trackBuffers[trackName] = await this.audioContext.decodeAudioData(arrayBuffer);
            this.duration = this.trackBuffers[trackName].duration;
        },
        
        async selectTrack(trackName) {
            if (this.currentTrack === trackName) return;
            
            const wasPlaying = this.playing;
            if (wasPlaying) this.stopPlayback();
            
            this.currentTrack = trackName;
            this.currentTime = 0;
            this.progress = 0;
            this.errorMsg = null;
            
            if (this.audioContext) {
                this.loading = true;
                try {
                    await this.loadTrack(trackName);
                    this.duration = this.trackBuffers[trackName].duration;
                    if (wasPlaying) this.startPlayback(0);
                } catch (e) {
                    console.error("Track Load Error:", e);
                    this.errorMsg = "Failed to load base track.";
                }
                this.loading = false;
            }
        },
        
        async togglePlay() {
            if (!this.audioContext) {
                await this.setupAudio();
            }
            
            if (this.audioContext.state === 'suspended') {
                await this.audioContext.resume();
            }
            
            if (this.playing) {
                this.stopPlayback();
            } else {
                this.startPlayback(this.currentTime);
            }
        },
        
        startPlayback(offset) {
            if (!this.trackBuffers[this.currentTrack]) return;
            
            this.sourceNode = this.audioContext.createBufferSource();
            this.sourceNode.buffer = this.trackBuffers[this.currentTrack];
            
            // Connect source to both dry and wet paths
            this.sourceNode.connect(this.dryGainNode);
            this.sourceNode.connect(this.convolverNode);
            
            this.sourceNode.onended = () => {
                // Only reset if it naturally ended, not if we stopped it manually
                if (this.playing && this.currentTime >= this.duration - 0.1) {
                    this.playing = false;
                    this.currentTime = 0;
                    this.progress = 0;
                    cancelAnimationFrame(this.animationFrame);
                    cancelAnimationFrame(this.visualizerAnimationFrame);
                    this.drawVisualizer(true); // draw flat line
                }
            };
            
            this.sourceNode.start(0, offset);
            this.startTime = this.audioContext.currentTime - offset;
            this.playing = true;
            
            this.updateLoop();
            this.drawVisualizer();
        },
        
        stopPlayback() {
            if (this.sourceNode) {
                this.sourceNode.stop();
                this.sourceNode.disconnect();
                this.sourceNode = null;
            }
            this.playing = false;
            cancelAnimationFrame(this.animationFrame);
            cancelAnimationFrame(this.visualizerAnimationFrame);
            this.drawVisualizer(true);
        },
        
        toggleBypass() {
            this.bypass = !this.bypass;
            if (this.audioContext) {
                this.dryGainNode.gain.value = this.bypass ? 1 : 0;
                this.wetGainNode.gain.value = this.bypass ? 0 : 1;
            }
        },
        
        updateLoop() {
            if (!this.playing) return;
            
            this.currentTime = this.audioContext.currentTime - this.startTime;
            if (this.currentTime >= this.duration) {
                this.currentTime = this.duration;
            }
            
            this.progress = (this.currentTime / this.duration) * 100;
            this.animationFrame = requestAnimationFrame(() => this.updateLoop());
        },
        
        seek(e) {
            if (!this.duration) return;
            const rect = this.$refs.progressContainer.getBoundingClientRect();
            const pos = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
            const newTime = pos * this.duration;
            
            if (this.playing) {
                this.stopPlayback();
                this.startPlayback(newTime);
            } else {
                this.currentTime = newTime;
                this.progress = (this.currentTime / this.duration) * 100;
            }
        },
        
        formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return `${m}:${s.toString().padStart(2, '0')}`;
        },
        
        drawVisualizer(flat = false) {
            const canvas = this.$refs.visualizer;
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            const width = canvas.width = canvas.offsetWidth;
            const height = canvas.height = canvas.offsetHeight;
            
            ctx.clearRect(0, 0, width, height);
            
            // Create gradient matching the screenshot
            const gradient = ctx.createLinearGradient(0, 0, width, 0);
            gradient.addColorStop(0, '#ff0000');
            gradient.addColorStop(0.25, '#ffff00');
            gradient.addColorStop(0.5, '#00ff00');
            gradient.addColorStop(0.75, '#00ffff');
            gradient.addColorStop(1, '#8B5CF6'); // Purple
            
            ctx.strokeStyle = gradient;
            ctx.lineWidth = 2;
            
            if (flat || !this.analyserNode) {
                ctx.beginPath();
                ctx.moveTo(0, height / 2);
                ctx.lineTo(width, height / 2);
                ctx.stroke();
                return;
            }
            
            const bufferLength = this.analyserNode.frequencyBinCount;
            const dataArray = new Uint8Array(bufferLength);
            this.analyserNode.getByteTimeDomainData(dataArray);
            
            ctx.beginPath();
            
            const sliceWidth = width * 1.0 / bufferLength;
            let x = 0;
            
            for (let i = 0; i < bufferLength; i++) {
                const v = dataArray[i] / 128.0;
                const y = v * height / 2;
                
                if (i === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
                
                x += sliceWidth;
            }
            
            ctx.lineTo(canvas.width, canvas.height / 2);
            ctx.stroke();
            
            if (this.playing) {
                this.visualizerAnimationFrame = requestAnimationFrame(() => this.drawVisualizer());
            }
        }
    };
}
