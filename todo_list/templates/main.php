<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($categories as $category) : ?>
                    <?php if($category['id'] == $_GET['project_id']) : ?>
                        <?php $classname = "main-navigation__list-item--active";?>
                    <?php else: ?>
                        <?php $classname = "";?>
                    <?endif;?>
                    <li class="main-navigation__list-item <?=$classname?>">
                        <a class="main-navigation__list-item-link" href="index.php?project_id=<?=$category["id"]?>"><?= $category["name"] ?></a>
                        <span class="main-navigation__list-item-count"><?= funTaskCount($tasks_list, $category["id"]) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="pages/form-project.html" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post" autocomplete="off">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="/" class="tasks-switch__item">Повестка дня</a>
                <a href="/" class="tasks-switch__item">Завтра</a>
                <a href="/" class="tasks-switch__item">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <?php if ($show_complete_tasks == 1) : ?>
                    <input class="checkbox__input visually-hidden show_completed" type="checkbox" checked>
                <?php else : ?>
                    <input class="checkbox__input visually-hidden show_completed" type="checkbox">
                <?php endif; ?>
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
            <?php foreach ($tasks_list_for_project as $task) : ?>
                <?php if ($task["status_ext"] == true && $show_complete_tasks == 0) : continue; ?>
                <?php elseif ($task["status_ext"] == true) : ?>
                    <tr class="tasks__item task task--completed">
                <?php else : ?>
                    <?php if(funTaskDeadline($task)):?>
                        <tr class="tasks__item task task--important">
                    <?php else: ?>
                        <tr class="tasks__item task">
                    <?php endif; ?>
                <?php endif; ?>
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <?php if ($task["status_ext"] == true) : ?>
                                <input class="checkbox__input visually-hidden" type="checkbox" checked>
                            <?php else : ?>
                                <input class="checkbox__input visually-hidden" type="checkbox">
                            <?php endif; ?>
                            <span class="checkbox__text"><?= esc($task["name"]); ?></span>
                        </label>
                    </td>
                    <td class="task__file"><a href="http://localhost/php_p.mishin/lesson_07/uploads/<?=esc($task['file_path']);?>"><?=esc($task['file_path']);?></a></td>
                    <?php if (esc($task["dt_deadline"]) == null) : ?>
                        <td class="task__date">Нет</td>
                    <?php else : ?>
                        <td class="task__date"><?= date("d.m.y", strtotime(esc($task["dt_deadline"])))  ?></td>
                    <?php endif; ?>
                    <td class="task__controls"></td>
                    </tr>
                <?php endforeach; ?>
        </table>
    </main>
</div>