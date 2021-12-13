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
                <li><a href="/">Homepage</a></li>
                <li><a href="/workshop/feed">Workshop</a></li>
                <li><a href="/donate">Donate</a></li>
                <li><a href="/posts">Gallery</a></li>
                <li><a href="/FaQ">FaQ</a></li>
                <li><a href="/contact">Contact us</a></li>
                <li><a href="/team">Teams</a></li>
                <?php if (!isset($_SESSION['evt'])) { ?>
                <li><a href="/criteria">Scoring Criteria</a></li>
                <li><a href="/register">Register</a></li>
                <li><a href="/login">Sign in</a></li>
                <?php } else { ?>
                <li><a href="/submit/">ส่งผลงาน</a></li>
                <li><a href="/criteria">Scoring Criteria</a></li>
                <li><a href="/logout">Sign out</a></li>
                <?php } ?>
            </ul>
            <label>Organizer</label>
            <ul>
                <?php if (!isset($_SESSION['evt2'])) { ?>
                <li><a href="/organize/">เข้าสู่ระบบทีมงาน</a></li>
                <?php } else { ?>
                <li><a href="/organize/home">เมนูหลัก</a></li>
                <li><a href="/profile">โปรไฟล์ของฉัน</a></li>
                <li><a href="/organize/attendees">รายชื่อผู้สมัคร</a></li>
                <li><a href="/organize/giveaway">บริหาร Giveaway</a></li>
                <li><a href="/organize/statics">สถิติ</a></li>
                <li><a href="/organize/logout">ออกจากระบบทีมงาน</a></li>
                <?php } ?>
            </ul>
        </div>
    </ul>
</section>