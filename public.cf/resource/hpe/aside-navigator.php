<section class="nav">
    <style type="text/css">
        aside.navigator_tab section.nav > ul { margin: 0px 0px 10px; padding-left: 5px !important; }
        aside.navigator_tab section.nav ul {
            padding-left: 25px;
            list-style-type: disc; white-space: nowrap;
        }
        aside.navigator_tab section.nav ul > li {
            padding-right: 10px;
            width: fit-content; height: 20px; line-height: 20px;
        }
        aside.navigator_tab section.nav ul > li.sub-detail { list-style-type: none; }
        aside.navigator_tab section.nav ul > li.seperator {
            width: 80%; height: 10px;
            background-image: linear-gradient(to bottom, transparent 0%, transparent 42.5%, var(--fade-black-7) 42.5%, var(--fade-black-7) 57.5%, transparent 57.5%, transparent 100%);
            list-style-type: none;
        }
        aside.navigator_tab section.nav ul > li.this-page, aside.navigator_tab section.nav ul > li.this-page a {
            color: var(--clr-bs-indigo); font-weight: bold;
            pointer-events: none;
        }
    </style>
    <ul>
        <div class="group">
            <label>Sitemap</label>
            <ul>
                <li><a href="/"><?=$_COOKIE['set_lang']=="th"?"หน้าหลัก":"Homepage"?></a></li>
                <li><a href="/workshop/feed"><?=$_COOKIE['set_lang']=="th"?"การประชุมเชิงปฏิบัติการ":"Workshop"?></a></li>
                <li><a href="/donate"><?=$_COOKIE['set_lang']=="th"?"บริจาค":"Donate"?></a></li>
                <li><a href="/posts"><?=$_COOKIE['set_lang']=="th"?"คลังภาพ":"Gallery"?></a></li>
                <li><a href="/FaQ"><?=$_COOKIE['set_lang']=="th"?"คำถามที่พบบ่อย":"FaQ"?></a></li>
                <li><a href="/contact"><?=$_COOKIE['set_lang']=="th"?"ติดต่อเรา":"Contact us"?></a></li>
                <li><a href="/team"><?=$_COOKIE['set_lang']=="th"?"คณะผู้ดำเนินงาน":"Teams"?></a></li>
                <li><a href="/announcement"><?=$_COOKIE['set_lang']=="th"?"ประกาศ":"Announcements"?></a></li>
                <?php if (!isset($_SESSION['evt'])) { ?>
                <li><a href="/criteria"><?=$_COOKIE['set_lang']=="th"?"เกณฑ์การพิจรณาคะแนน":"Scoring Criteria"?></a></li>
                <li><a href="/register"><?=$_COOKIE['set_lang']=="th"?"ลงทะเบียน":"Register"?></a></li>
                <li><a href="/login"><?=$_COOKIE['set_lang']=="th"?"เข้าสู่ระบบ":"Sign in"?></a></li>
                <?php } else { ?>
                <li><a href="/submit/"><?=$_COOKIE['set_lang']=="th"?"ส่งผลงาน":"Submit speech"?></a></li>
                <li><a href="/criteria"><?=$_COOKIE['set_lang']=="th"?"เกณฑ์การพิจรณาคะแนน":"Scoring Criteria"?></a></li>
                <li><a href="/logout"><?=$_COOKIE['set_lang']=="th"?"ออกจากระบบ":"Sign out"?></a></li>
                <?php } ?>
            </ul>
            <label><?=$_COOKIE['set_lang']=="th"?"ผู้ดำเนินงาน":"Organizer"?></label>
            <ul>
                <?php if (!isset($_SESSION['evt2'])) { ?>
                <li><a href="/organize/"><?=$_COOKIE['set_lang']=="th"?"เข้าสู่ระบบทีมงาน":"Organizer sign-in"?></a></li>
                <?php } else { ?>
                <li><a href="/organize/home"><?=$_COOKIE['set_lang']=="th"?"เมนูหลัก":"Home menu"?></a></li>
                <li class="sub-detail"><?=$_COOKIE['set_lang']=="th"?"เกี่ยวกับ":"About"?></li>
                <li><a href="/organize/profile"><?=$_COOKIE['set_lang']=="th"?"โปรไฟล์ของฉัน":"My profile"?></a></li>
                <li class="seperator">&nbsp;</li>
                <li><a href="/organize/grant-permission"><?=$_COOKIE['set_lang']=="th"?"คำขออนุญาติอ่านคอมเม้น":"Comment view request"?></a></li>
                <li><a href="/organize/send-email"><?=$_COOKIE['set_lang']=="th"?"ส่งอีเมลแจ้งเตือน":"Notify with e-mail"?></a></li>
                <li class="sub-detail"><?=$_COOKIE['set_lang']=="th"?"การแข่งขัน":"Competition"?></li>
                <li><a href="/organize/attendees"><?=$_COOKIE['set_lang']=="th"?"รายชื่อผู้สมัคร":"Applicant list"?></a></li>
                <li><a href="/organize/mark-grade"><?=$_COOKIE['set_lang']=="th"?"ลงคะแนนวีดีโอคลิป":"Grade speech"?></a></li>
                <li hidden><a href="/organize/rate-rank"><?=$_COOKIE['set_lang']=="th"?"จัดลำดับและตัดสิน":"Rank speech"?></a></li>
                <li class="seperator">&nbsp;</li>
                <li><a href="/organize/scoreboard"><?=$_COOKIE['set_lang']=="th"?"กระดานคะแนน":"Scoreboard"?></a></li>
                <li class="sub-detail"><?=$_COOKIE['set_lang']=="th"?"การบริจาค":"Donate"?></li>
                <li><a href="/organize/giveaway"><?=$_COOKIE['set_lang']=="th"?"บริหาร Giveaway":"Giveaway management"?></a></li>
                <li><a href="/organize/donation"><?=$_COOKIE['set_lang']=="th"?"รายการที่รับบริจาค":"Donation list"?></a></li>
                <li><a href="/donation-doc"><?=$_COOKIE['set_lang']=="th"?"เอกสารการบริจาค":"Donation documents"?></a></li>
                <li class="sub-detail"><?=$_COOKIE['set_lang']=="th"?"สถิติต่างๆ":"Statics"?></li>
                <li><a href="/organize/statics"><?=$_COOKIE['set_lang']=="th"?"สถิติของโครงการ":"Event statics"?></a></li>
                <li class="seperator">&nbsp;</li>
                <li><a href="/organize/logout"><?=$_COOKIE['set_lang']=="th"?"ออกจากระบบทีมงาน":"Organizer sign-out"?></a></li>
                <?php } ?>
            </ul>
        </div>
    </ul>
</section>