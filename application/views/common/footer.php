<?php if (isset($js_footer ) && !empty($js_footer)): ?>
    <?php for ($i = 0; $i < count( $js_footer ); $i++): ?>
        <?php if(!empty($js_footer[$i])): ?>
            <script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . $js_footer[$i]); ?>.js"></script>
        <?php endif; ?>
    <?php endfor; ?>
<?php endif; ?>    
    </body>
</html>