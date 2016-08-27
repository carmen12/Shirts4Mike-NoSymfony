<?php
require_once ("inc/config.php");
//var_dump(ROOT_PATH . "inc/products.php");die;
include(ROOT_PATH . "inc/products.php");
//include(ROOT_PATH . "shirts/shirts.php");
$recent = get_products_recent();

$pageTitle = "Unique T-Shirts designed by a frog";
$section = "home";
include(ROOT_PATH . 'inc/header.php'); ?>

		<div class="section banner">

			<div class="wrapper">

				<img class="hero" src="<?php echo BASE_URL . 'img/mike-the-frog.png'?>" alt="Mike the Frog says:">
				<div class="button">
					<a href="<?php echo BASE_URL . 'shirts/'?>">
						<h2>Hey, I&rsquo;m Mike!</h2>
						<p>Check Out My Shirts</p>
					</a>
				</div>
			</div>

		</div>

		<div class="section shirts latest">

			<div class="wrapper">

				<h2>Mike&rsquo;s Latest Shirts</h2>

				<ul class="products">
					<?php
						$list_view_html = "";
						foreach($recent as $prod){
								$list_view_html = get_list_view_html($prod) . $list_view_html;
						}
						echo $list_view_html;
					?>
				</ul>
			</div>
		</div>
<?php include(ROOT_PATH . 'inc/footer.php'); ?>


















