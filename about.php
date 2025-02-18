<?php require 'includes/header.php'; ?>
<?php require 'config/config.php'; ?>

<!-- HERO SECTION -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('images/hero_1.jpg');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold">About Us</h1>
        <div class="custom-breadcrumbs">
          <a href="<?php echo APP_URL; ?>">Home</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong>About</strong></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT SECTION -->
<section class="site-section about-section" style="padding: 4rem 0; background: #f1f5f8;">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Column: Image -->
      <div class="col-md-6 mb-4">
        <img src="images/job-search.png" alt="About Us" class="img-fluid rounded" style="width: 100%; height: auto; object-fit: cover;">
      </div>
      <!-- Right Column: Story -->
      <div class="col-md-6">
        <h2 class="text-black mb-4">Our Story</h2>
        <p style="font-size: 1rem; line-height: 1.6; color: #333;">
          Welcome to JobBridge, your trusted partner in connecting talented professionals with meaningful career opportunities across Lebanon. Founded in Beirut, we understand the unique challenges of the local job market and are committed to bridging the gap between job seekers and employers.
        </p>
        <p style="font-size: 1rem; line-height: 1.6; color: #333;">
          Our mission is to empower individuals and organizations by providing a modern, efficient, and reliable platform for recruitment. With innovative technology and a dedicated team, we strive to create lasting connections that help our community thrive.
        </p>
        <p style="font-size: 1rem; line-height: 1.6; color: #333;">
          Join us at JobBridge and take the first step towards a brighter future.
        </p>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
