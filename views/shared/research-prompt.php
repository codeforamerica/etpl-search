<?php if($config["show-research-prompt"] == "true") { ?>

	<?php if(!isset($_COOKIE["research-prompt-closed"]) || isset($_GET["force_research_prompt"])) { ?>
	  <a target="_blank" href="<?php echo (getenv("PRODUCTION") == "true" ? getenv("RESEARCH_PROMPT_LINK") : "about:blank"); ?>" class="program-link-wrapper" id="research-prompt" data-research-prompt-position="<?php echo $research_prompt_position; ?>">
	    <div class="program special">
	      <div class="close"></div>
	      <h1><?php echo (getenv("PRODUCTION") == "true" ? getenv("RESEARCH_PROMPT_TITLE") : "RESEARCH_PROMPT_TITLE"); ?></h1>
	      <div class="description"><?php echo (getenv("PRODUCTION") == "true" ? getenv("RESEARCH_PROMPT_SUBTITLE") : "RESEARCH_PROMPT_SUBTITLE"); ?></div>
	      <div class="description small"><?php echo (getenv("PRODUCTION") == "true" ? getenv("RESEARCH_PROMPT_DESCRIPTION") : "RESEARCH_PROMPT_DESCRIPTION"); ?></div>
	      <button class="full-width small"><?php echo (getenv("PRODUCTION") == "true" ? getenv("RESEARCH_PROMPT_BUTTON_TEXT") : "RESEARCH_PROMPT_BUTTON_TEXT"); ?></button>
	    </div>
	  </a>
	<?php } ?>

<?php } ?>