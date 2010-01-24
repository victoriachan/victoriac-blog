<?php
/**
 * Bottom Content
 */
 ?>
    <?php if ($content_bottom): ?>
      <div id="content_bottom" class="content_bottom"><div class="inner">
        <!-- REGION content_bottom -->
        <?php print $content_bottom; ?>
      </div></div> <!-- /.content_bottom -->
    <?php endif; ?> 
 
    <?php if ($footer || $footer_message): ?>
    <div id="footer" class="footer">
      <div class="inner">
      <?php print $footer; ?>
      <?php if ($footer_message): ?>
        <div id="footer-message"><?php print $footer_message; ?></div>
      <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>  
   
  <?php print $closure; ?>
  <?php $is_front? print('</div>') : '' //closes home_banner ?>
</div><!-- /second_body --></body>
</html>