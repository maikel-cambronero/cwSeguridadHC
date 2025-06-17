<?php
require_once '../routes/rutas.php';
session_start();

// Verificación de sesión
if (!isset($_SESSION['usuario'])) {
  header('Location: ' . BASE_PATH . '/index.php');
  exit;
}

// Incluir el header
require_once 'layout/header.php';
?>

<!-- ! Main -->
<main class="main users chart-page" id="skip-target">
  <div class="container">

    <h2 class="main-title">Panel de Control - <?= htmlspecialchars($_SESSION['usuario']) ?> 👋 </h2>

    <div class="row stat-cards">

      <div class="col-md-6 col-xl-3">
        <article class="stat-cards-item">
          <div class="stat-cards-icon primary">
            <i data-feather="bar-chart-2" aria-hidden="true"></i>
          </div>
          <div class="stat-cards-info">
            <p class="stat-cards-info__num">1478 286</p>
            <p class="stat-cards-info__title">Total visits</p>
            <p class="stat-cards-info__progress">
              <span class="stat-cards-info__profit success">
                <i data-feather="trending-up" aria-hidden="true"></i>4.07%
              </span>
              Last month
            </p>
          </div>
        </article>
      </div>

      <div class="col-md-6 col-xl-3">
        <article class="stat-cards-item">
          <div class="stat-cards-icon warning">
            <i data-feather="file" aria-hidden="true"></i>
          </div>
          <div class="stat-cards-info">
            <p class="stat-cards-info__num">1478 286</p>
            <p class="stat-cards-info__title">Total visits</p>
            <p class="stat-cards-info__progress">
              <span class="stat-cards-info__profit success">
                <i data-feather="trending-up" aria-hidden="true"></i>0.24%
              </span>
              Last month
            </p>
          </div>
        </article>
      </div>

      <div class="col-md-6 col-xl-3">
        <article class="stat-cards-item">
          <div class="stat-cards-icon purple">
            <i data-feather="file" aria-hidden="true"></i>
          </div>
          <div class="stat-cards-info">
            <p class="stat-cards-info__num">1478 286</p>
            <p class="stat-cards-info__title">Total visits</p>
            <p class="stat-cards-info__progress">
              <span class="stat-cards-info__profit danger">
                <i data-feather="trending-down" aria-hidden="true"></i>1.64%
              </span>
              Last month
            </p>
          </div>
        </article>
      </div>

      <div class="col-md-6 col-xl-3">
        <article class="stat-cards-item">
          <div class="stat-cards-icon success">
            <i data-feather="feather" aria-hidden="true"></i>
          </div>
          <div class="stat-cards-info">
            <p class="stat-cards-info__num">1478 286</p>
            <p class="stat-cards-info__title">Total visits</p>
            <p class="stat-cards-info__progress">
              <span class="stat-cards-info__profit warning">
                <i data-feather="trending-up" aria-hidden="true"></i>0.00%
              </span>
              Last month
            </p>
          </div>
        </article>
      </div>

    </div>



  </div>
</main>

<?php
// Incluir el footer
require_once 'layout/footer.php';
?>