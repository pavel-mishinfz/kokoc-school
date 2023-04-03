<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($categories as $category) : ?>
                    <?php $classname = (isset($_GET['project_id']) && $category['id'] == $_GET['project_id']) ? "main-navigation__list-item--active" : "" ?>
                    <li class="main-navigation__list-item <?= $classname ?>">
                        <a class="main-navigation__list-item-link" href="index.php?project_id=<?= $category["id"] ?>"><?= $category["name"] ?></a>
                        <span class="main-navigation__list-item-count"><?= funTaskCount($tasks_list, $category["id"]) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="add-project.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php?q" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="q" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <?php $classname = "tasks-switch__item--active"; ?>
                <a href="index.php" class="tasks-switch__item <?= empty($_GET['switch']) ? $classname : '' ?>">Все задачи</a>
                <a href="index.php?switch=1" class="tasks-switch__item <?= $_GET['switch'] == 1 ? $classname : '' ?>">Повестка дня</a>
                <a href="index.php?switch=2" class="tasks-switch__item <?= $_GET['switch'] == 2 ? $classname : '' ?>">Завтра</a>
                <a href="index.php?switch=3" class="tasks-switch__item <?= $_GET['switch'] == 3 ? $classname : '' ?>">Просроченные</a>
            </nav>

            <?php $param = $_SERVER['QUERY_STRING'] ? ("?" . $_SERVER['QUERY_STRING']) : ""; ?>
            <form action="index.php<?= $param; ?>" method="post">
                <label class="checkbox">
                    <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                    <?php if ($show_complete_tasks == 1) : ?>
                        <input class="checkbox__input visually-hidden" name="show_comleted" value="0" type="submit">
                        <input class="checkbox__input visually-hidden" type="checkbox" checked>
                    <?php else : ?>
                        <input class="checkbox__input visually-hidden" name="show_comleted" value="1" type="submit">
                    <?php endif; ?>
                    <span class="checkbox__text">Показывать выполненные</span>
                </label>
            </form>
        </div>

        <?php if (count($tasks_list_for_project) || empty($_GET['q'])) : ?>
            <table class="tasks">
                <?php foreach ($tasks_list_for_project as $task) : ?>
                    <?php if ($task["status_ext"] == true && $show_complete_tasks == 0) : continue; ?>
                    <?php elseif ($task["status_ext"] == true) : ?>
                        <tr class="tasks__item task task--completed">
                    <?php else : ?>
                        <tr class="tasks__item task <?= (funTaskDeadline($task) ? "task--important" : "") ?>">
                    <?php endif; ?>
                    <td class="task__select">
                        <form action="index.php<?= $param; ?>" method="post">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden" name="task_id" value="<?= $task['id'] ?>" type="submit">
                                <input class="checkbox__input visually-hidden" type="checkbox" <?= ($task["status_ext"] ? 'checked' : '') ?>>
                                <span class="checkbox__text"><?= $task["name"]; ?></span>
                            </label>
                        </form>
                    </td>
                    <td class="task__file"><a href="http://localhost/kokoc-school/kokoc-school/todo_list/uploads/<?= $task['file_path']; ?>"><?= $task['file_path']; ?></a></td>
                    <?php if ($task["dt_deadline"] == null) : ?>
                        <td class="task__date">Нет</td>
                    <?php else : ?>
                        <td class="task__date"><?= date("d.m.y", strtotime($task["dt_deadline"]))  ?></td>
                    <?php endif; ?>
                        <td class="task__controls"></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <?= 'Ничего не найдено по вашему запросу'; ?>
        <?php endif; ?>
    </main>
</div>