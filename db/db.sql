CREATE DATABASE task;

CREATE TABLE users (
    id BIGSERIAL NOT NULL PRIMARY KEY ,
    name VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL 
);

CREATE TABLE tasks (
    task_id BIGSERIAL PRIMARY KEY NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type type_enum DEFAULT 'basic',
    status status_enum DEFAULT 'open',
    created_at TIMESTAMP NOT NULL, 
    assignee_id BIGINT REFERENCES users(id)
);  




