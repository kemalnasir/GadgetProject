<?php if ($_SESSION['id']) { ?>
    <div class="brand clearfix" style="background-color: black; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center;">
        <a href="#" class="logo" style="font-size: 24px; color: #fff; text-decoration: none;">Gadget Tutor</a>

        <ul class="ts-profile-nav" style="list-style: none; margin: 0; padding: 0; margin-left: auto;">
            <li style="display: inline-block; margin-left: 20px;">
                <a href="../index.php" style="color: #fff; text-decoration: none;"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>
<?php } else { ?>
    <div class="brand clearfix" style="background-color: #333; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center;">
        <p class="copy-right" style="color: #fff; margin: 0;">Copyright &copy; 2024 Take Note Portal.</p>
        <span class="menu-btn" style="cursor: pointer;"><i class="fa fa-bars" style="color: #fff; font-size: 24px;"></i></span>
    </div>
<?php } ?>
