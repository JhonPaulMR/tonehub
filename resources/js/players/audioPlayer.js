export default function audioPlayer(srcUrl) {
    return {
        playing: false,
        loading: false,
        errorMsg: null,
        progress: 0,
        currentTime: 0,
        duration: 0,
        
        audioContext: null,
        sourceNode: null,
        analyserNode: null,
        visualizerAnimationFrame: null,
        
        audioBuffer: null,
        animationFrame: null,
        startTime: 0,
        
        async init() {
            this.drawVisualizer(true);
        },
        
        async setupAudio() {
            if (this.audioContext) return;
            this.loading = true;
            
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                this.audioContext = new AudioContext();
                
                const response = await fetch(srcUrl);
                if (!response.ok) throw new Error('Audio file not found');
                const arrayBuffer = await response.arrayBuffer();
                this.audioBuffer = await this.audioContext.decodeAudioData(arrayBuffer);
                this.duration = this.audioBuffer.duration;
                
                this.analyserNode = this.audioContext.createAnalyser();
                this.analyserNode.fftSize = 2048;
                this.analyserNode.connect(this.audioContext.destination);
                
            } catch (e) {
                console.error("Audio Setup Error:", e);
                this.errorMsg = "Failed to load audio preview.";
                this.audioContext = null;
            } finally {
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
            if (!this.audioBuffer) return;
            
            this.sourceNode = this.audioContext.createBufferSource();
            this.sourceNode.buffer = this.audioBuffer;
            this.sourceNode.connect(this.analyserNode);
            
            this.sourceNode.onended = () => {
                if (this.playing && this.currentTime >= this.duration - 0.1) {
                    this.playing = false;
                    this.currentTime = 0;
                    this.progress = 0;
                    cancelAnimationFrame(this.animationFrame);
                    cancelAnimationFrame(this.visualizerAnimationFrame);
                    this.drawVisualizer(true);
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
            
            const gradient = ctx.createLinearGradient(0, 0, width, 0);
            gradient.addColorStop(0, '#ff0000');
            gradient.addColorStop(0.25, '#ffff00');
            gradient.addColorStop(0.5, '#00ff00');
            gradient.addColorStop(0.75, '#00ffff');
            gradient.addColorStop(1, '#8B5CF6');
            
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
