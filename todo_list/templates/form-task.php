<div class="content">
      <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($categories as $category) : ?>
                    <li class="main-navigation__list-item">
                        <a class="main-navigation__list-item-link" href="index.php?project_id=<?=$category["id"]?>"><?= $category["name"] ?></a>
                        <span class="main-navigation__list-item-count"><?= funTaskCount($tasks_list, $category["id"]) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
      </section>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['name']) ? "form__input--error" : "";?>
            <input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=esc(getPostVal('name'))?>" placeholder="Введите название">
            <p class="form__message"><?=(isset($errors['name']) ? $errors['name'] : '');?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?php $classname = isset($errors['project']) ? "form__input--error" : "";?>
            <select class="form__input form__input--select <?=$classname;?>" name="project" id="project">
                <?php foreach($categories as $category): ?>
                    <option value="<?=$category['id']?>"
                    <?php if(isset($form_task['project']) && $category['id'] == $form_task['project']):?> selected <?php endif; ?>><?=esc($category['name']);?></option>
                <?endforeach;?>
            </select>
            <p class="form__message"><?=(isset($errors['project']) ? $errors['project'] : '');?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>
            <?php $classname = isset($errors['date']) ? "form__input--error" : "";?>
            <input class="form__input form__input--date <?=$classname;?>" type="text" name="date" id="date" value="<?=esc(getPostVal('date'))?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message"><?=(isset($errors['date']) ? $errors['date'] : '');?></p>
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>
            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="<?=esc(getPostVal('file'))?>">
              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
    </div>