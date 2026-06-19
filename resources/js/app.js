

import irPlayer from './players/irPlayer';
import audioPlayer from './players/audioPlayer';
import tagInput from './components/tagInput';

document.addEventListener('alpine:init', () => {
    Alpine.data('irPlayer', irPlayer);
    Alpine.data('audioPlayer', audioPlayer);
    Alpine.data('tagInput', tagInput);
});
