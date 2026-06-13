function generateTshirtPreview(canvasId, baseImageUrl, designImageUrl, options = {}) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const scale = options.scale || 0.7;
    const offsetX = options.offsetX || 0;
    const offsetY = options.offsetY || 0;

    let baseImg = new Image();
    let designImg = new Image();
    let loaded = 0;

    function tryDraw() {
        if (loaded < 2) return;
        canvas.width = baseImg.width;
        canvas.height = baseImg.height;
        ctx.drawImage(baseImg, 0, 0);
        let dw = baseImg.width * scale;
        let dh = designImg.height * (dw / designImg.width);
        let dx = (baseImg.width - dw) / 2 + (baseImg.width * offsetX / 100);
        let dy = (baseImg.height - dh) / 2 + (baseImg.height * offsetY / 100);
        ctx.drawImage(designImg, dx, dy, dw, dh);
    }

    baseImg.onload = () => { loaded++; tryDraw(); };
    designImg.onload = () => { loaded++; tryDraw(); };
    baseImg.src = baseImageUrl;
    designImg.src = designImageUrl;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tshirt-preview').forEach(canvas => {
        generateTshirtPreview(canvas.id, canvas.dataset.baseUrl, canvas.dataset.designUrl, {
            scale: canvas.dataset.scale || 0.7,
            offsetX: canvas.dataset.offsetX || 0,
            offsetY: canvas.dataset.offsetY || 0,
        });
    });
});