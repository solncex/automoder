<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" <?=(empty($data['current_page']) || $data['current_page']=='home')?'class="active"':''?>><a href="/">Главная</a></li>
                <li role="presentation" <?=(!empty($data['current_page']) && $data['current_page']=='adv')?'class="active"':''?>><a href="/adv/">Объявления</a></li>
                <li role="presentation" <?=(!empty($data['current_page']) && $data['current_page']=='source')?'class="active"':''?>><a href="/source/">Источники</a></li>
                <li role="presentation" <?=(!empty($data['current_page']) && $data['current_page']=='rules')?'class="active"':''?>><a href="/rules/">Правила модерации</a></li>
            </ul>
        </nav>
        <h3 class="text-muted">AutoModer</h3>
    </div>
    <div class="row marketing">
        <div class="col-lg-12">

            <?=(!empty($data['content']))?$data['content']:''?>

        </div>
    </div>
    <footer class="footer">
        <p>&copy; 2017 Хамитов Ильхам</p>
    </footer>
</div> <!-- /container -->
</body>
</html>