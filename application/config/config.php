<?php
    require_once __DIR__ . '/autoload.php';
    $lang = new language;

    $lang::translate([
        'lv' => [
            '{nav.home}'        => 'galvenā',
            '{nav.about}'       => 'par mani',
            '{nav.contact}'     => 'kontakti',
            '{nav.language}'    => 'valoda',
            '{profile.name}'    => 'Sveiki. Es esmu Yuu Hirokabe...',
            '{profile.job}'     => 'Mājaslapu izstrādātājs un baltās cepures hakeris',
            '{about.title}'     => 'Par Yuu Hirokabe',
            '{about.content}'   => '<b>Alekss Brigmanis-Briģis</b> arī pazīstams kā <b>Yuu Hirokabe</b> ir mājaslapu izstrādātājs un kiberdrošības speciālists.<br>
            Viņš sāka nodarboties ar programmēšanu <b>Tukuma 3. pamatskolā</b> un pašlaik studē kiberdrošību <b>PIKC Saldus tehnikumā.</b><br><br>
            Yuu Hirokabe, gluži kā citi, aizraujas ar videospēlēm un spēlē jau no agras bērnības. Pateicoties spēlēm, viņš sāka apgūt programmēšanu un kā pirmo programmēšanas valodu viņš apguva - <b>PAWN</b> - veca kodēšanas valoda.'
        ],
        'en' => [
            '{nav.home}'        => 'home',
            '{nav.about}'       => 'about me',
            '{nav.contact}'     => 'contact',
            '{nav.language}'    => 'language',
            '{profile.name}'    => 'Hello. I\'m Yuu Hirokabe...',
            '{profile.job}'     => 'Web developer and White hat hacker',
            '{about.title}'     => 'About Yuu Hirokabe',
            '{about.content}'   => '
                 <b>Alekss Brigmanis-Briģis</b> also known as <b>Yuu Hirokabe</b> is website developer and cybersecurity technician.<br>
                He started programming at elementary school <b>Tukuma 3. pamatskola</b> and now studying cybersecurity in <b>PIKC Saldus technical school.</b><br><br>
                Yuu Hirokabe, like most people, likes video games and has played them from an early childhood. Because of games, he started learning about programming and, as the first language, he learned <b>PAWN</b> - an old scripting language.'
        ],
        'ru' => [
            '{nav.home}'        => 'главная',
            '{nav.about}'       => 'про меня',
            '{nav.contact}'     => 'контакты',
            '{nav.language}'    => 'язык',
            '{profile.name}'    => 'Привет. Я Юу Хирокабе...',
            '{profile.job}'     => 'Веб-разработчик и хакер белай шляпки',
            '{about.title}'     => 'Про Юу Хирокабе',
            '{about.content}'   => '<b>Алекс Бригманис-Бригис</b>
                также известный как <b>Юу Хирокабе</b>
                является разработчиком веб-сайтов и специалистом по кибербезопасности.<br>
                Он начал программировать
                <b>Третей Тукумской начальной школе</b>
                и сейчас изучает кибербезопасность
                <b>ПИКЦ Салдусский техникум</b><br><br>
                Юу Хирокабе, как и другие, любит играть в видеоигры и играет с раннего возраста. Благодаря играм, он начал изучать программирования. Как первый язык программирования он Использовал,
                <b>PAWN</b> - который яевляется старым языком программированием.'
        ],
        'ja' => [
            '{nav.home}'        => 'ホーム',
            '{nav.about}'       => '私について',
            '{nav.contact}'     => '連絡先',
            '{nav.language}'    => '言語',
            '{profile.name}'    => 'こんにちは。ゆうヒロカベです。',
            '{profile.job}'     => 'ウエブデベロッパーと白いハットハッカー',
            '{about.title}'     => 'ゆうヒロカベについて',
            '{about.content}'   => 'そろそろ'
        ]
    ]);

    require_once __DIR__ . '/../vendor/route/App.php';
    require_once __DIR__ . '/../vendor/route/Request.php';
    require_once __DIR__ . '/../vendor/route/Route.php';
    require_once __DIR__ . '/../vendor/route/function.php';
    require_once __DIR__ . '/route.php';