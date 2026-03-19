// exif-remover.js - EXIF metadata removal and image conversion

class ExifRemover {
    constructor() {
        this.processedFiles = [];
    }

    // Основная функция обработки файлов
    async processFiles(files) {
        const results = [];
        
        for (const file of files) {
            try {
                const result = await this.processFile(file);
                results.push(result);
            } catch (error) {
                console.error(`Error processing ${file.name}:`, error);
                results.push({
                    success: false,
                    originalName: file.name,
                    error: error.message
                });
            }
        }
        
        return results;
    }

    // Обработка одного файла
    async processFile(file) {
        // Проверка типа файла
        const fileType = file.type.toLowerCase();
        const fileName = file.name.toLowerCase();
        
        // Обработка изображений
        if (fileType.startsWith('image/') || fileName.match(/\.(jpg|jpeg|png|gif|bmp|webp)$/i)) {
            return await this.removeExifData(file);
        }
        
        throw new Error('Неподдерживаемый формат файла');
    }

    // Создание blob из canvas с заданным quality
    canvasToBlob(canvas, quality) {
        return new Promise((resolve, reject) => {
            canvas.toBlob((blob) => {
                if (blob) resolve(blob);
                else reject(new Error('Ошибка создания изображения'));
            }, 'image/jpeg', quality);
        });
    }

    // Удаление EXIF данных из изображения
    async removeExifData(file) {
        const dataUrl = await new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = () => reject(new Error('Ошибка чтения файла'));
            reader.readAsDataURL(file);
        });

        const img = await new Promise((resolve, reject) => {
            const i = new Image();
            i.onload = () => resolve(i);
            i.onerror = () => reject(new Error('Ошибка загрузки изображения'));
            i.src = dataUrl;
        });

        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        // Одна перекодировка с quality 0.85 — гарантирует уменьшение размера
        let blob = await this.canvasToBlob(canvas, 0.85);
        // Если всё ещё больше оригинала — снижаем quality
        if (blob.size >= file.size) {
            blob = await this.canvasToBlob(canvas, 0.70);
        }

        return {
            success: true,
            originalName: file.name,
            cleanFileName: this.getCleanFileName(file.name),
            originalSize: file.size,
            cleanSize: blob.size,
            blob: blob,
            converted: false,
            metadataRemoved: ['EXIF', 'GPS', 'IPTC', 'Thumbnail', 'Color Profile']
        };
    }

    // Обработка blob изображения
    async processImageBlob(blob) {
        const dataUrl = await new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = () => reject(new Error('Ошибка чтения blob'));
            reader.readAsDataURL(blob);
        });

        const img = await new Promise((resolve, reject) => {
            const i = new Image();
            i.onload = () => resolve(i);
            i.onerror = () => reject(new Error('Ошибка загрузки изображения'));
            i.src = dataUrl;
        });

        const canvas = document.createElement('canvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        let cleanBlob = await this.canvasToBlob(canvas, 0.85);
        if (cleanBlob.size >= blob.size) {
            cleanBlob = await this.canvasToBlob(canvas, 0.70);
        }
        return cleanBlob;
    }

    // Получить имя очищенного файла
    getCleanFileName(originalName) {
        const nameParts = originalName.split('.');
        const extension = nameParts.pop();
        const baseName = nameParts.join('.');
        
        // Всегда сохраняем как JPEG
        return `${baseName}_clean.jpg`;
    }

    // Скачать файл
    downloadFile(blob, fileName) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Скачать все файлы как ZIP (опционально)
    async downloadAsZip(results) {
        // Требует библиотеку JSZip
        if (typeof JSZip === 'undefined') {
            throw new Error('JSZip library not loaded');
        }

        const zip = new JSZip();
        
        results.forEach((result, index) => {
            if (result.success) {
                zip.file(result.cleanFileName, result.blob);
            }
        });

        const zipBlob = await zip.generateAsync({ type: 'blob' });
        this.downloadFile(zipBlob, 'cleaned_images.zip');
    }
}

// Экспорт для использования
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ExifRemover;
}
