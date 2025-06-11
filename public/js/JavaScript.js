// Configuración de los carruseles
const carousels = {
    right: {
        images: [
            "assets/FotoPalitosPollo.jpg",
            "assets/FotoGazpacho.jpg",
            "assets/FotoGyozas.jpg"
        ],
        currentIndex: 0,
        imageElement: document.getElementById("carousel-image-derecha"),
        buttons: document.querySelectorAll("button[data-carousel='right']")
    },
    left: {
        images: [
            "assets/FotoChoco.jpg",
            "assets/FotoFiletEmpanado.jpg",
            "assets/FotoUdon.jpg"
        ],
        currentIndex: 0,
        imageElement: document.getElementById("carousel-image-izquierda"),
        buttons: document.querySelectorAll("button[data-carousel='left']")
    }
};

// Función para actualizar un carrusel
const updateCarousel = (carousel) => {
    const { images, currentIndex, imageElement, buttons } = carousel;

    // Cambia la imagen 
    imageElement.src = images[currentIndex]; // Cambia la imagen

    // Actualiza los botones
    buttons.forEach((button, index) => {
        button.classList.toggle("bg-gray-500", index === currentIndex); // Resalta el botón activo
        button.classList.toggle("bg-gray-300", index !== currentIndex); // Restaura el color de los demás
    });
};

// Función para cambiar la imagen automáticamente
const startCarousel = (carouselKey) => {
    const carousel = carousels[carouselKey];
    setInterval(() => {
        carousel.currentIndex = (carousel.currentIndex + 1) % carousel.images.length; // Avanza al siguiente índice
        updateCarousel(carousel);
    }, 3000); // Cambia cada 3 segundos
};

// Manejar clics en los botones
Object.keys(carousels).forEach((key) => {
    const carousel = carousels[key];
    carousel.buttons.forEach((button) => {
        button.addEventListener("click", () => {
            carousel.currentIndex = parseInt(button.getAttribute("data-index")); // Obtiene el índice del botón
            updateCarousel(carousel); // Actualiza el carrusel
        });
    });
});

// Inicializa los carruseles
Object.keys(carousels).forEach((key) => {
    updateCarousel(carousels[key]);
    startCarousel(key);
});