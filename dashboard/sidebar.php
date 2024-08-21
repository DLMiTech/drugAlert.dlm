<?php
$pages = substr($_SERVER['SCRIPT_NAME'], strripos($_SERVER['SCRIPT_NAME'], "/")+1);
?>

<div class="side-bar">
    <div class="side-bar-head">
        <div class="info mb-2">
            <img src="../assets/images/person.jpeg" alt="" style="width: 3.5rem; height: 3.5rem; border-radius: 50%">
        </div>
        <?php
        if ($_SESSION['role'] == 3){
            ?>
            <h6 class="mb-0"><Span class="text-danger fw-bold">A</Span>dmin</h6>
            <small class="m-0"><?=  $_SESSION['username']?></small>
            <?php
        }elseif ($_SESSION['role'] == 2){
            ?><h6 class="mb-0"><Span class="text-danger fw-bold">M</Span>anager</h6> <small class="m-0"><?=  $_SESSION['username']?></small><?php
        }else{
            ?><h6 class="mb-0"><Span class="text-danger fw-bold">S</Span>aller</h6> <small class="m-0"><?=  $_SESSION['username']?></small><?php
        }
        ?>

    </div>

    <div class="side-bar-menu">
        <div class="menu-items">
            <ul>
                <li>
                    <a href="index" class="<?= $pages == "index.php"? 'activeLink':'';?>">
                        <span><i class="bi bi-speedometer"></i></span>
                        <p class="m-0">DASHBOARD</p>
                    </a>
                </li>


                <li>
                    <a href="administering" class="<?= $pages == "administering.php"? 'activeLink':'';?>">
                        <span><i class="bi bi-bag-plus-fill"></i></span>
                        <p class="m-0">ADMINISTERING</p>
                    </a>
                </li>

                <?php
                if ($_SESSION['role'] == 3 || $_SESSION['role'] == 2){
                    ?>
                    <li>
                        <a href="drugs" class="<?= $pages == "drugs.php"? 'activeLink':'';?>">
                            <span><i class="bi bi-capsule"></i></span>
                            <p class="m-0">ADD DRUGS</p>
                        </a>
                    </li>

                    <li>
                        <a href="user" class="<?= $pages == "user.php"? 'activeLink':'';?>">
                            <span><i class="bi bi-person-hearts"></i></span>
                            <p class="m-0">USERS</p>
                        </a>
                    </li>
                    <?php
                }
                ?>


                <li>
                    <a href="profile" class="<?= $pages == "profile.php"? 'activeLink':'';?>">
                        <span><i class="bi bi-person-lines-fill"></i></span>
                        <p class="m-0">PROFILE</p>
                    </a>
                </li>

                <hr>
                <li>
                    <a id="logoutBtn" class="text-danger" style="cursor: pointer">
                        <span><i class="bi bi-box-arrow-left"></i></span>
                        <p class="m-0">Logout</p>
                    </a>
                </li>


            </ul>
        </div>
    </div>

    <div class="side-bar-footer">
        <div class="info">
            <p class="m-0">Powered by Name © 2024</p>
            <p class="m-0">v 1.1.2</p>
        </div>
    </div>
</div>