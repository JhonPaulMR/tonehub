import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

export default function tagInput(initialTags = []) {
    return {
        tagify: null,
        init() {
            const input = this.$refs.tagInput;
            this.tagify = new Tagify(input, {
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
                pattern: /^[a-zA-Z0-9\\s-]+$/,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
            
            if (initialTags.length > 0) {
                this.tagify.addTags(initialTags);
            }
            
            // Clean up on component destruction
            this.$cleanup(() => {
                this.tagify.destroy();
            });
        }
    };
}
