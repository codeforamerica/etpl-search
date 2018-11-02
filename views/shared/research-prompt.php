<?php if($config["show-research-prompt"] == 1) { ?>

	<?php if(!isset($_COOKIE["research-prompt-closed"]) || isset($_GET["force_research_prompt"])) { ?>
	  <a target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLSdZXAQdsNTtLYRGLd2VS5W-74i0tDeedgj19pRxWq9IiAha1w/viewform" class="program-link-wrapper" id="research-prompt" data-research-prompt-position="<?php echo $research_prompt_position; ?>">
	    <div class="program special">
	      <div class="close"></div>
	      <h1>Paid Research Study</h1>
	      <div class="description">Share your experience using this site and get a $25 Amazon gift card.</div>
	      <div class="description small">Code for America is looking to understand how people find subsidized/paid training. If selected, we'll contact you to schedule a call.</div>
	      <button class="full-width small">Sign Up</button>
	    </div>
	  </a>
	<?php } ?>

<?php } ?>