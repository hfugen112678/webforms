<div class="footer">
    <div class="footer-inner">
        <div class="footer-content">
            <span class="bigger-120">
                <span class="blue bolder">TREC Global</span>
                &COPY; <?php echo date('Y'); ?>
            </span>
        </div>
    </div>
</div><!-- /.footer -->
<script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'jquery-2.1.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'jquery-ui.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'ace-elements.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . 'ace.min.js'); ?>"></script>
<?php if (isset($js_footer ) && !empty($js_footer)): ?>
    <?php for ($i = 0; $i < count( $js_footer ); $i++): ?>
        <?php if(!empty($js_footer[$i])): ?>
            <script type="text/javascript" src="<?php echo base_url($this->config->item('js_directory') . $js_footer[$i]); ?>.js"></script>
        <?php endif; ?>
    <?php endfor; ?>
<?php endif; ?>