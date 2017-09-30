<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>success</title>
</head>
<body>

</body>
<script>
    alert('<?=$this->e($message)?>');
    if ('<?=$this->e($redirect_url)?>'){
        location.href = '<?=$this->e($redirect_url)?>';
    }else{
        history.go(-1);
    }
</script>
</html>