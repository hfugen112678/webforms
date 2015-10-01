<div data-sidebar-hover="true" data-sidebar-scroll="true" data-sidebar="true" id="sidebar" class="sidebar responsive">
    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
    </script>
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        
    </div><!-- /.sidebar-shortcuts -->
    <ul style="top: 0px;" class="nav nav-list">
        <li class="<?php echo iif($this->uri->segments[1] == 'operations' || $this->uri->segments[1] == 'filedot', 'active open', ''); ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-briefcase"></i>
                <span class="menu-text"> Overtime </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="<?php echo iif($this->uri->segments[1] == 'operations', 'active', ''); ?>">
                    <a href="<?php echo site_url('/operations'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        My Overtime                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php if (in_array($this->session->userdata('positionid'), $this->config->item('management_positions'))): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'filedot', 'active', ''); ?>">
                    <a href="<?php echo site_url('/filedot'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Filed                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <li class="<?php echo iif($this->uri->segments[1] == 'adjustments' || $this->uri->segments[1] == 'adjustmentlead' || $this->uri->segments[1] == 'adjustmentsecurity' || $this->uri->segments[1] == 'adjustmentfinance', 'active open', ''); ?>">
            <a href="" class="dropdown-toggle">
                <i class="menu-icon fa fa-adjust"></i>
                <span class="menu-text"> Adjustments </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="<?php echo iif($this->uri->segments[1] == 'adjustments', 'active', ''); ?>">
                    <a href="<?php echo site_url('/adjustments'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        My Adjustments                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php if (in_array($this->session->userdata('positionid'), $this->config->item('management_positions'))): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'adjustmentlead', 'active', ''); ?>">
                    <a href="<?php echo site_url('/adjustmentlead'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Filed                       
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('department') == 11): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'adjustmentsecurity', 'active', ''); ?>">
                    <a href="<?php echo site_url('/adjustmentsecurity'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Security                       
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('department') == 5): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'adjustmentfinance', 'active', ''); ?>">
                    <a href="<?php echo site_url('/adjustmentfinance'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Finance                   
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <li class="<?php echo iif($this->uri->segments[1] == 'transportation' || $this->uri->segments[1] == 'filedtranspo' || $this->uri->segments[1] == 'transpohrapproval', 'active open', ''); ?>">
            <a href="" class="dropdown-toggle">
                <i class="menu-icon fa fa-bus"></i>
                <span class="menu-text"> Transportation </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="<?php echo iif($this->uri->segments[1] == 'transportation', 'active', ''); ?>">
                    <a href="<?php echo site_url('/transportation'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        File                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php if (in_array($this->session->userdata('positionid'), $this->config->item('management_positions'))): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'filedtranspo', 'active', ''); ?>">
                    <a href="<?php echo site_url('/filedtranspo'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        TL Approval                       
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('department') === 6): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'transpohrapproval', 'active', ''); ?>">
                    <a href="<?php echo site_url('/transpohrapproval'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        HR Approval                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <li class="<?php echo iif($this->uri->segments[1] == 'leaves' || $this->uri->segments[1] == 'leaveapproval' || $this->uri->segments[1] == 'leavecredits' || $this->uri->segments[1] == 'medcerts', 'active open', ''); ?>">
            <a href="" class="dropdown-toggle">
                <i class="menu-icon fa fa-calendar"></i>
                <span class="menu-text"> Leaves </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="<?php echo iif($this->uri->segments[1] == 'leaves', 'active', ''); ?>">
                    <a href="<?php echo site_url('/leaves'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        My Leaves                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php if (in_array($this->session->userdata('positionid'), $this->config->item('management_positions'))): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'leaveapproval', 'active', ''); ?>">
                    <a href="<?php echo site_url('/leaveapproval'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        For Approval                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
                <?php if ($this->session->userdata('department') === 6): ?>
                <li class="<?php echo iif($this->uri->segments[1] == 'leavecredits', 'active', ''); ?>">
                    <a href="<?php echo site_url('/leavecredits'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Credits                        
                    </a>
                    <b class="arrow"></b>
                </li>                
                <li class="<?php echo iif($this->uri->segments[1] == 'medcerts', 'active', ''); ?>">
                    <a href="<?php echo site_url('/medcerts'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Medical Cert                        
                    </a>
                    <b class="arrow"></b>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php if (in_array($this->session->userdata('positionid'), $this->config->item('management_positions'))): ?>
        <li class="<?php echo iif($this->uri->segments[1] == 'agentlist', 'active open', ''); ?>">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-group"></i>
                <span class="menu-text"> Agents </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="<?php echo iif($this->uri->segments[1] == 'agentlist', 'active', ''); ?>">
                    <a href="<?php echo site_url('/agentlist'); ?>">
                        <i class="menu-icon fa fa-caret-right"></i>
                        List                        
                    </a>
                    <b class="arrow"></b>
		</li>
            </ul>
        </li>
        <?php endif; ?>		
        <li class="">
            <a href="<?php echo site_url('/logout'); ?>">
                <i class="menu-icon fa fa-power-off"></i>
                <span class="menu-text"> Log out </span>
            </a>
            <b class="arrow"></b>
        </li>
    </ul><!-- /.nav-list -->
</div> <!-- /.sidebar -->   