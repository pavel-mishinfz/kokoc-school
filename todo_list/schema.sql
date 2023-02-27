CREATE DATABASE todo_list
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE todo_list;

CREATE TABLE users (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                CHAR(64) NOT NULL,
    dt_registration     TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    email               CHAR(128) NOT NULL UNIQUE,
    password            CHAR(64) NOT NULL
);
CREATE TABLE projects (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        CHAR(64) NOT NULL,
    user_id     INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE tasks (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            CHAR(255) NOT NULL,
    dt_add          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_ext      BOOLEAN NOT NULL,
    file_path       CHAR(255) DEFAULT NULL,
    dt_deadline     CHAR(10) DEFAULT NULL,
    user_id         INT,
    project_id      INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

CREATE UNIQUE INDEX user_email ON users(email);

CREATE INDEX project_name_idx ON projects(name);

CREATE INDEX task_name_idx ON tasks(name);
CREATE INDEX task_dt_add_idx ON tasks(dt_add);
CREATE INDEX task_status_ext_idx ON tasks(status_ext);
CREATE INDEX task_file_path_idx ON tasks(file_path);
CREATE INDEX task_dt_deadline_idx ON tasks(dt_deadline);

CREATE INDEX user_dt_registration_idx ON users(dt_registration);
CREATE INDEX user_password_idx ON users(password);
CREATE INDEX user_name ON users(name);

CREATE FULLTEXT INDEX task_name_search ON tasks(name);
