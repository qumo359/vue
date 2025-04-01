// composables/useCkeditor.ts
import { ref, onMounted } from 'vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Font from '@ckeditor/ckeditor5-font/src/font';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';

export function useCkeditor(editorRef: ReturnType<typeof ref>, data: string) {
    onMounted(() => {
        ClassicEditor
            .create(editorRef.value, {
                licenseKey: '...', // Вставьте ваш licenseKey, если есть
                plugins: [Essentials, Bold, Italic, Font, Paragraph],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ],
            })
            .then(newEditor => {
                newEditor.setData(data);
                newEditor.model.document.on('change:data', () => {
                    data = newEditor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
    });

    return { editorRef, data };
}
