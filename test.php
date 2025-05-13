<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vehicle Monitoring & Registration</title>
  <link rel="stylesheet" href="Angelo_ThesisWork/swiper-bundle.min.css" />
  <style>
    /* === ROOT VARIABLES === */
    :root {
      --header-height: 3.5rem;
      --blue-color: #80050d;
      --gold-color:  #efb954;
      --red-color: #80050d;
      --white-color: hsl(0, 0%, 100%);
      --black-color: hsl(210, 8%, 8%);
      --maroonColor: #80050d;
      --yellowColor: #efb954;
      --whiteColor: #ffffff;
      --light-gray: #ebebeb;

      --body-font: "Roboto", sans-serif;
      --biggest: 5rem;
      --big: 2rem;
      --normal: 0.938rem;
      --small: 0.813rem;
      --font-regular: 400;
      --font-semi-bold: 600;
      --font-bold: 700;
      --z-tooltip: 10;
      --z-fixed: 100;
    }

    @media screen and (min-width: 1150px) {
      :root {
        --biggest: 15rem;
        --big: 6rem;
        --normal: 1rem;
        --small: 0.875rem;
      }
    }

    /* === RESET === */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--body-font);
      font-size: var(--normal);
      background-color: var(--black-color);
      color: var(--white-color);
    }

    ul { list-style: none; }
    a { text-decoration: none; }
    img { max-width: 100%; display: block; }

    /* === UTILITIES === */
    .container {
      max-width: 90vw; /* Or use vh if you prefer */
  margin: 0 auto;
  padding: 0 1.5rem;
    }

    .main {
      overflow: hidden;
    }

    /* === COLORS === */
    .car__red { --color-car: var(--red-color); }
    .car__gold { --color-car: var(--gold-color); }
    .car__blue { --color-car: var(--blue-color); }

    /* === HOME SECTION === */
    .home__article {
      position: relative;
      width: 100%;
      height: 100vh;
      padding-top: 8rem;
    }

    .hp1,
    .hp2 {
      position: absolute;
      width: 100%;
    }

    .hp1 {
      height: 20%;
      background-color: var(--color-car);
      top: 0;
      left: 0;
      overflow: hidden;
    }

    .hp1-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

    .hp2 {
      height: 80%;
      background-color: var(--light-gray);
      bottom: 0;
      left: 0;
    }

    .home__content {
  position: relative;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 10vh; /* Adjust this value as needed */
  z-index: 1;
  text-align: center;
}

    .home__titles {
      text-align: left;
  max-width: 61.73vh; /* 700px */
  margin: 0 auto 1.76vh; /* 2rem */
}

.home__subtitle {
      font-size: 3.5vh;
      color: var(--black-color);
      font-weight: var(--font-semi-bold);
      margin-top: 2vh;
      margin-left: 2vh;
    }

    .home__title {
  font-size: 6vh;
  font-weight: bold;
  color: var(--yellowColor);
  text-shadow:
    1px 1px 0 var(--maroonColor),
    2px 2px 0 var(--maroonColor),
    3px 3px 0 var(--maroonColor),
    4px 4px 0 var(--maroonColor),
    5px 5px 10px rgba(128, 5, 13, 0.7);
}


    .home__image {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  height: 26.47vh; /* 300px */
}

.home__img {
  width: 33.52vh; /* 380px */
  transform: translateY(2.64vh); /* 3rem */
  opacity: 0;
  transition: transform 0.8s 0.3s, opacity 0.4s 0.3s;
}

    .swiper-slide-active .home__img {
      transform: translateY(0);
      opacity: 1;
    }

    .home__interaction {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    /* === SWIPER === */
   

  

    .swiper-pagination-bullet {
      width: 5vh; 
      height: auto; 
      background-color: transparent;
      border: 2px solid var(--maroonColor);
      border-radius: 50%;
      opacity: 0.5;
      transition: 0.3s;
    }

    .swiper-pagination-bullet-active {
      background-color: var(--maroonColor);
      opacity: 1;
    }

    /* === RESPONSIVE === */
    @media screen and (max-width: 768px) {
      .home__subtitle { font-size: 1.5rem; }
      .home__title { font-size: 2rem; }
      .home__img { width: 26.47vh; }
    }

    @media screen and (min-width: 1150px) {
      .home__content {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        text-align: left;
      }

      .home__titles {
        text-align: left;
        margin: 0;
      }

      .home__image {
        height: auto;
        align-items: center;
      }

      .home__img {
        width: 80vh
      }

      .swiper-pagination {
        flex-direction: column;
        right: 2rem;
        left: auto;
        top: 50%;
        transform: translateY(-50%);
      }
    }

    .back-button {
  position: fixed;
  top: 22vh;
  left: 2vh;
  z-index: 9999;
  display: inline-block;
  padding: 1vh 2vh;
  background-color: var(--maroonColor);
  color: var(--yellowColor);
  font-weight: var(--font-semi-bold);
  border: 2px solid var(--yellowColor);
  border-radius: 0.5rem;
  font-size: var(--normal);
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
  cursor: pointer;

  opacity: 0;
  transform: translateX(-20px);
  animation: fadeSlideIn 0.8s ease-out forwards;
  animation-delay: 0.5s; /* Optional: slight delay to sync with page elements */
}

@keyframes fadeSlideIn {
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
  </style>
</head>
<body>
  <main class="main">
    <section class="home">
      <div class="home__swiper swiper">
        <div class="swiper-wrapper">
        <a href="javascript:history.back()" class="back-button">← Back</a>

      

          <!-- Blue Car Slide -->
          <article class="home__article car__blue swiper-slide">
          <div class="hp1">
  <img src="PNG/red_box.png" alt="Top Banner" class="hp1-img" />
</div>
            <div class="hp2"></div>
            <div class="home__content container">
              <div class="home__titles">
              <h1 class="home__title">Vehicle Reservation Calendar</h1>
                <h3 class="home__subtitle">The calendar gives you a visual schedule of all vehicle bookings. This helps you save time and plan better.</h3>
               
              </div>
              <div class="home__image">
                <img src="Angelo_ThesisWork/cal.png" alt="Blue Car" class="home__img" />
              </div>
            </div>
          </article>

          <!-- Gold Car Slide -->
          <article class="home__article car__gold swiper-slide">
            <div class="hp1"></div>
            <div class="hp2"></div>
            <div class="home__content container">
              <div class="home__titles">
              <h1 class="home__title">Mobile-Friendly</h1>
                <h3 class="home__subtitle">is designed to be fully responsive and mobile-friendly. allowing users to access the platform anytime, anywhere, using their smartphones.</h3>
              </div>
              <div class="home__image">
                <img src="Angelo_ThesisWork/phone.png" alt="Gold Car" class="home__img" />
              </div>
            </div>
          </article>

          <!-- Red Car Slide -->
          <article class="home__article car__red swiper-slide">
            <div class="hp1"></div>
            <div class="hp2"></div>
            <div class="home__content container">
              <div class="home__titles">
              <h1 class="home__title">Vehicle Reservation</h1>
                <h3 class="home__subtitle">This system is designed to help staff and personnel efficiently schedule and manage vehicle reservations</h3>
              </div>
              <div class="home__image">
                <img src="PNG/civic.webp" alt="Red Car" class="home__img" />
              </div>
            </div>
          </article>

        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="home__interaction"></div>
    </section>
  </main>

  <script src="Angelo_ThesisWork/gsap.min.js"></script>
  <script src="Angelo_ThesisWork/swiper-bundle.min.js"></script>
  <script src="Angelo_ThesisWork/main.js"></script>
</body>
</html>
