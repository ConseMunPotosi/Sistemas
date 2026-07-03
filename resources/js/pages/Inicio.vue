<template>
  <div class="page-container">

    <img src="../../../public/images/home/img1.png" class="img img1" />
    <img src="../../../public/images/home/img2.png" class="img img2" />
    <img src="../../../public/images/home/img3.png" class="img img3" />
    <img src="../../../public/images/home/img4.png" class="img img4" />

  </div>
</template>

<style scoped>
.page-container {
  position: relative;
  width: 100%;
  min-height: 100vh;

  background-image: url('../../../public/images/fondo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  overflow: hidden;
}

/* BASE */
.img {
  position: absolute;
  max-width: 100%;
  height: auto;
}

/* DESKTOP (BASE) */
.img1 {
  width: 88vh;
  left: 0;
  top: 0;
  z-index: 1;
  animation: entrarIzquierda 1.5s ease-out forwards;
}

.img2 {
  width: 18vw;
  left: 34vw;

  z-index: 3;
  animation: entrarDerecha 1.8s ease-out forwards;
}

.img3 {
  width: 18vw;
  left: 26vw;
  z-index: 2;
  animation: entrarArriba 2s ease-out forwards;
}

.img4 {
  width: 8vw;
  left: 39vw;
  top: 63vh;
  z-index: 1;
  animation: entrarAbajo 2s ease-out forwards;
}

/* 🔥 TABLET */
@media (max-width: 1024px) {
  .img1 { width: 60vw; }
  .img2 { width: 35vw; left: 30vw; }
  .img3 { width: 25vw; left: 25vw; }
  .img4 { width: 20vw; left: 45vw; top: 10vw;}
}

/* 🔥 MÓVIL */
@media (max-width: 600px) {
  .page-container {
    min-height: 120vw;
  }

  .img1 {
    width: 90vw;
    left: 5vw;
    top: 0;
  }

  .img2 {
    width: 70vw;
    left: 15vw;
    top: 20vh;
  }

  .img3 {
    width: 60vw;
    left: 20vw;
    top: 45vh;
  }

  .img4 {
    width: 50vw;
    left: 25vw;
    top: 30vw;
  }
}

/* ANIMACIONES */
@keyframes entrarIzquierda {
  from { transform: translateX(-200px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes entrarDerecha {
  from { transform: translateX(200px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes entrarArriba {
  from { transform: translateY(-200px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes entrarAbajo {
  from { transform: translateY(200px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
