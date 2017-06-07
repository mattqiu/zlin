<?php defined('InIMall') or exit('Access Invalid!');?>
<header>
    <div class="container">
        <div class="row">

            <!-- Logo section -->
            <div class="col-md-4">
                <!-- Logo. -->
                <div class="logo">
                    <h1><!--<a href="#">-->微商城<span class="bold"></span><!--</a>--></h1>
                    <p class="meta">后台管理系统</p>
                </div>
                <!-- Logo ends -->
            </div>

            <!-- Button section -->
            <div class="col-md-4">
                <!-- Buttons -->
                <ul class="nav nav-pills"></ul>
            </div>

            <!-- Data section -->

            <div class="col-md-4">
                <div class="header-data">

                    <!-- Traffic data -->


                    <!-- Members data -->
                    <div class="hdata" style='width:80px;'>
                        <div class="mcol-left">
                            <!-- Icon with blue background -->
                            <i class="icon-user bblue"></i>
                        </div>
                        <div class="mcol-right">
                            <!-- Number of visitors -->
                            <table>
                                <td><!--<a href="index.php" align='left'>-->商户会员<!--</a>--></td>
                                <tr><td> <em><?php echo $_SESSION['username'];?></em>
                                    </td></table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <span style='width:250px;'>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <!-- revenue data -->

                </div>
            </div>

        </div>
    </div>
</header>