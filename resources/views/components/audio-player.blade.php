@props(['item'])

@if($item->category === 'IR' && $item->preset_file_path)
<!-- Advanced IR Player using Web Audio API -->
<div class="rounded-xl p-6 bg-surface-container border border-outline-variant shadow-sm relative overflow-hidden" 
     x-data="irPlayer('{{ asset('storage/' . $item->preset_file_path) }}', { clean: '{{ asset('samples/clean.wav') }}', distortion: '{{ asset('samples/distortion.wav') }}', metal: '{{ asset('samples/metal.wav') }}' })">
    <div class="absolute top-0 left-0 w-1 bg-secondary h-full"></div>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-label-bold font-label-bold text-on-surface">Test IR in Realtime</h3>
        <span class="px-2 py-1 bg-surface-container-high rounded text-[10px] font-mono text-secondary border border-secondary/30">WEB AUDIO API</span>
    </div>
    
    <div class="bg-surface-container-low p-4 rounded-lg border border-outline-variant flex flex-col gap-4">
        <!-- Track Selection -->
        <div class="flex gap-2">
            <button @click="selectTrack('clean')" :class="currentTrack === 'clean' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface'" class="flex-1 py-2 rounded-lg text-label-sm font-label-bold transition-colors">Clean</button>
            <button @click="selectTrack('distortion')" :class="currentTrack === 'distortion' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface'" class="flex-1 py-2 rounded-lg text-label-sm font-label-bold transition-colors">Distortion</button>
            <button @click="selectTrack('metal')" :class="currentTrack === 'metal' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant hover:text-on-surface'" class="flex-1 py-2 rounded-lg text-label-sm font-label-bold transition-colors">Metal</button>
        </div>

        <div class="flex items-center gap-4">
            <button @click="togglePlay" :disabled="loading || errorMsg" class="w-12 h-12 rounded-full bg-secondary text-on-primary flex items-center justify-center hover:bg-secondary-fixed transition-transform hover:scale-105 shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-2xl" x-show="!loading" x-text="playing ? 'pause' : 'play_arrow'"></span>
                <span class="material-symbols-outlined text-2xl animate-spin" x-show="loading">progress_activity</span>
            </button>
            
            <div class="flex-grow flex items-center gap-4">
                <div class="text-label-sm text-on-surface-variant font-mono whitespace-nowrap min-w-[70px]">
                    <span x-text="formatTime(currentTime)">0:00</span> / <span x-text="formatTime(duration)">0:00</span>
                </div>
                <div class="flex-grow bg-transparent h-12 relative flex items-center cursor-pointer" @click="seek($event)" x-ref="progressContainer">
                    <!-- Audio Visualizer Canvas -->
                    <canvas x-ref="visualizer" class="absolute inset-0 w-full h-full z-0"></canvas>
                    <!-- Progress Overlay Cursor -->
                    <div class="absolute left-0 top-0 h-full border-r border-on-surface/50 pointer-events-none z-10 transition-all duration-75" :style="`width: ${progress}%`"></div>
                    <!-- Unplayed mask to dim the waveform ahead of the cursor -->
                    <div class="absolute right-0 top-0 h-full bg-background/50 pointer-events-none z-10 transition-all duration-75" :style="`width: ${100 - progress}%`"></div>
                </div>
            </div>
            
            <!-- Bypass Toggle -->
            <button @click="toggleBypass" class="flex flex-col items-center justify-center px-3 py-1 rounded border transition-colors shrink-0" :class="bypass ? 'border-outline-variant text-on-surface-variant' : 'border-secondary text-secondary bg-secondary/10'">
                <span class="material-symbols-outlined text-[18px]">graphic_eq</span>
                <span class="text-[10px] font-label-bold uppercase mt-1" x-text="bypass ? 'Bypass' : 'IR Active'"></span>
            </button>
        </div>
        
        <div x-show="errorMsg" class="mt-2 text-error text-label-sm flex items-center gap-1" x-transition>
            <span class="material-symbols-outlined text-[16px]">error</span>
            <span x-text="errorMsg"></span>
        </div>
    </div>
</div>



@endif

@if($item->wet_sample_path)
<!-- Standard Player -->
<div class="rounded-xl p-6 bg-surface-container border border-outline-variant shadow-sm relative overflow-hidden mt-6" x-data="audioPlayer('{{ asset('storage/' . $item->wet_sample_path) }}')">
    <div class="absolute top-0 left-0 w-1 bg-primary h-full"></div>
    <h3 class="text-label-bold font-label-bold text-on-surface mb-4">Preview Audio</h3>
    
    <div class="bg-surface-container-low p-4 rounded-lg border border-outline-variant flex items-center gap-4">
        <button @click="togglePlay" class="w-12 h-12 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-primary-fixed transition-transform hover:scale-105 shrink-0">
            <span class="material-symbols-outlined text-2xl" x-text="playing ? 'pause' : 'play_arrow'"></span>
        </button>
        
        <div class="flex-grow flex items-center gap-4">
            <div class="text-label-sm text-on-surface-variant font-mono whitespace-nowrap min-w-[70px]">
                <span x-text="formatTime(currentTime)">0:00</span> / <span x-text="formatTime(duration)">0:00</span>
            </div>
            <div class="flex-grow bg-transparent h-12 relative flex items-center cursor-pointer" @click="seek($event)" x-ref="progressContainer">
                <!-- Audio Visualizer Canvas -->
                <canvas x-ref="visualizer" class="absolute inset-0 w-full h-full z-0"></canvas>
                <!-- Progress Overlay Cursor -->
                <div class="absolute left-0 top-0 h-full border-r border-on-surface/50 pointer-events-none z-10 transition-all duration-75" :style="`width: ${progress}%`"></div>
                <!-- Unplayed mask to dim the waveform ahead of the cursor -->
                <div class="absolute right-0 top-0 h-full bg-background/50 pointer-events-none z-10 transition-all duration-75" :style="`width: ${100 - progress}%`"></div>
            </div>
        </div>

    </div>
</div>
@endif
