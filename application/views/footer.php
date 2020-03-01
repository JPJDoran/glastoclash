    <script src="/httpdocs/foundation/js/vendor/jquery.js"></script>
    <script src="/httpdocs/foundation/js/vendor/what-input.js"></script>
    <script src="/httpdocs/foundation/js/vendor/foundation.js"></script>
    <script src="/httpdocs/foundation/js/app.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    
    <?php if(isset($js)): ?>
    	<script src="/httpdocs/js/<?= $js ?>.js"></script>
    <?php endif; ?>

    <?php if(isset($flatpickr) && $flatpickr): ?>
      <script src="/httpdocs/plugins/flatpickr/js/flatpickr.js"></script>
    <?php endif; ?>
    
    <?php if(isset($sumoselect) && $sumoselect): ?>
      <script src="/httpdocs/plugins/sumoselect/js/jquery.sumoselect.min.js"></script>
      <link rel="stylesheet" href="/httpdocs/plugins/sumoselect/css/sumoselect.min.css">
    <?php endif; ?>
  </body>
</html>