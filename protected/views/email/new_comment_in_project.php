<p>Новый комментарий!!!</p>

<p>Проект: <?=$project->name?></p>

<p>Текст: <?=$comment->getPlainText()?></p>

<p>Ссылка: <?=$project->getViewUrl($comment->user->role != 'admin' ? $comment->user->role : '');?></p>