--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: access_token; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE access_token (
    token character varying(256) NOT NULL,
    "idUser" integer NOT NULL,
    "expiresIn" integer NOT NULL,
    id integer NOT NULL,
    "createdAt" integer NOT NULL
);


ALTER TABLE access_token OWNER TO project_user;

--
-- Name: access_token_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE access_token_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE access_token_id_seq OWNER TO project_user;

--
-- Name: access_token_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE access_token_id_seq OWNED BY access_token.id;


--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE auth_assignment OWNER TO project_user;

--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE auth_item (
    name character varying(64) NOT NULL,
    type integer NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_item OWNER TO project_user;

--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE auth_item_child OWNER TO project_user;

--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_rule OWNER TO project_user;

--
-- Name: d_gender; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE d_gender (
    id integer NOT NULL,
    name character varying(256) NOT NULL
);


ALTER TABLE d_gender OWNER TO project_user;

--
-- Name: d_gender_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE d_gender_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE d_gender_id_seq OWNER TO project_user;

--
-- Name: d_gender_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE d_gender_id_seq OWNED BY d_gender.id;


--
-- Name: j_user_user_group; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE j_user_user_group (
    "idUser" integer NOT NULL,
    "idUserGroup" integer NOT NULL
);


ALTER TABLE j_user_user_group OWNER TO project_user;

--
-- Name: migration; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE migration OWNER TO project_user;

--
-- Name: project; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE project (
    id integer NOT NULL,
    name character varying(1024) NOT NULL,
    description text,
    "startedAt" integer NOT NULL,
    "endedAt" integer,
    "isActive" boolean
);


ALTER TABLE project OWNER TO project_user;

--
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE project_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_id_seq OWNER TO project_user;

--
-- Name: project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE project_id_seq OWNED BY project.id;


--
-- Name: project_user; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE project_user (
    id integer NOT NULL,
    "idUser" integer NOT NULL,
    "idProject" integer NOT NULL,
    "attachedAt" integer NOT NULL,
    "isActive" boolean
);


ALTER TABLE project_user OWNER TO project_user;

--
-- Name: project_user_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE project_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_user_id_seq OWNER TO project_user;

--
-- Name: project_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE project_user_id_seq OWNED BY project_user.id;


--
-- Name: project_user_record; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE project_user_record (
    id integer NOT NULL,
    "idProjectUser" integer NOT NULL,
    "time" integer NOT NULL
);


ALTER TABLE project_user_record OWNER TO project_user;

--
-- Name: project_user_record_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE project_user_record_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_user_record_id_seq OWNER TO project_user;

--
-- Name: project_user_record_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE project_user_record_id_seq OWNED BY project_user_record.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE "user" (
    id integer NOT NULL,
    login character varying(256) NOT NULL,
    "passwordHash" character varying(64) NOT NULL,
    lastname character varying(1024),
    firstname character varying(1024),
    middlename character varying(1024),
    "idGender" integer,
    email character varying(256) NOT NULL,
    "isActive" boolean
);


ALTER TABLE "user" OWNER TO project_user;

--
-- Name: user_group; Type: TABLE; Schema: public; Owner: project_user; Tablespace: 
--

CREATE TABLE user_group (
    id integer NOT NULL,
    name character varying(1024) NOT NULL,
    "mainRole" character varying(64) NOT NULL
);


ALTER TABLE user_group OWNER TO project_user;

--
-- Name: user_group_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE user_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_group_id_seq OWNER TO project_user;

--
-- Name: user_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE user_group_id_seq OWNED BY user_group.id;


--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: project_user
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO project_user;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: project_user
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY access_token ALTER COLUMN id SET DEFAULT nextval('access_token_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY d_gender ALTER COLUMN id SET DEFAULT nextval('d_gender_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project ALTER COLUMN id SET DEFAULT nextval('project_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project_user ALTER COLUMN id SET DEFAULT nextval('project_user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project_user_record ALTER COLUMN id SET DEFAULT nextval('project_user_record_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY user_group ALTER COLUMN id SET DEFAULT nextval('user_group_id_seq'::regclass);


--
-- Data for Name: access_token; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO access_token VALUES ('expired-token', 3, 0, 1, 0);
INSERT INTO access_token VALUES ('admin-token', 3, 1924905600, 2, 1924905600);
INSERT INTO access_token VALUES ('chief-token', 4, 1924905600, 4, 1924905600);
INSERT INTO access_token VALUES ('manager-token', 5, 1924905600, 6, 1924905600);
INSERT INTO access_token VALUES ('performer-token', 7, 1924905600, 7, 1924905600);
INSERT INTO access_token VALUES ('disabled-token', 8, 1924905600, 8, 1924905600);


--
-- Name: access_token_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('access_token_id_seq', 8, true);


--
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO auth_assignment VALUES ('admin', '3', NULL);
INSERT INTO auth_assignment VALUES ('chief', '4', NULL);
INSERT INTO auth_assignment VALUES ('manager', '5', NULL);
INSERT INTO auth_assignment VALUES ('performer', '7', NULL);
INSERT INTO auth_assignment VALUES ('performer', '8', NULL);


--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO auth_item VALUES ('performer', 1, 'Project performer', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('manager', 1, 'Project manager', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('chief', 1, 'Organization chief', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('admin', 1, 'Administrator', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.deleteAll', 2, 'Delete all access tokens', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.delete', 2, 'Delete access token', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.deleteOwn', 2, 'Delete own access token', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.viewAll', 2, 'View all access tokens', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.view', 2, 'View access token', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('access-token.viewOwn', 2, 'View own access token', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.create', 2, 'Create user', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.viewAll', 2, 'View all users', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.view', 2, 'View user', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.viewOwn', 2, 'View own user', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.update', 2, 'Update user', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.updateOwn', 2, 'Update own user', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.delete', 2, 'Delete user', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.projects', 2, 'View user projects list', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.ownProjects', 2, 'View own user projects list', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.userGroups', 2, 'View user usergroups list', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.ownUserGroups', 2, 'View own user usergroups list', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.permissions', 2, 'View user permissions', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user.ownPermissions', 2, 'View own user permissions', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.create', 2, 'Create project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.viewAll', 2, 'View all projects', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.view', 2, 'View project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.viewOwn', 2, 'View own project', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.update', 2, 'Update project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.updateOwn', 2, 'Update own project', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.delete', 2, 'Delete project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.users', 2, 'View project users list', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project.ownUsers', 2, 'View own project users list', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user-group.users', 2, 'View user group users', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user-group.permissions', 2, 'View user group permissions', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('user-group.ownPermissions', 2, 'View own user group permissions', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project-user.create', 2, 'Create link between user and project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project-user.createOwn', 2, 'Create link between user and own project', 'isOwner', NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project-user.delete', 2, 'Delete link between user and project', NULL, NULL, 1459149659, 1459149659);
INSERT INTO auth_item VALUES ('project-user.deleteOwn', 2, 'Delete link between user and own project', 'isOwner', NULL, 1459149659, 1459149659);


--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO auth_item_child VALUES ('access-token.deleteOwn', 'access-token.delete');
INSERT INTO auth_item_child VALUES ('access-token.viewOwn', 'access-token.view');
INSERT INTO auth_item_child VALUES ('user.viewOwn', 'user.view');
INSERT INTO auth_item_child VALUES ('user.updateOwn', 'user.update');
INSERT INTO auth_item_child VALUES ('user.ownProjects', 'user.projects');
INSERT INTO auth_item_child VALUES ('user.ownUserGroups', 'user.userGroups');
INSERT INTO auth_item_child VALUES ('user.ownPermissions', 'user.permissions');
INSERT INTO auth_item_child VALUES ('project.viewOwn', 'project.view');
INSERT INTO auth_item_child VALUES ('project.updateOwn', 'project.update');
INSERT INTO auth_item_child VALUES ('project.ownUsers', 'project.users');
INSERT INTO auth_item_child VALUES ('user-group.ownPermissions', 'user-group.permissions');
INSERT INTO auth_item_child VALUES ('project-user.createOwn', 'project-user.create');
INSERT INTO auth_item_child VALUES ('project-user.deleteOwn', 'project-user.delete');
INSERT INTO auth_item_child VALUES ('performer', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('performer', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('performer', 'user.viewOwn');
INSERT INTO auth_item_child VALUES ('performer', 'user.updateOwn');
INSERT INTO auth_item_child VALUES ('performer', 'user.ownProjects');
INSERT INTO auth_item_child VALUES ('performer', 'user.ownUserGroups');
INSERT INTO auth_item_child VALUES ('performer', 'user.ownPermissions');
INSERT INTO auth_item_child VALUES ('performer', 'project.viewOwn');
INSERT INTO auth_item_child VALUES ('performer', 'project.ownUsers');
INSERT INTO auth_item_child VALUES ('performer', 'user-group.ownPermissions');
INSERT INTO auth_item_child VALUES ('manager', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('manager', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('manager', 'user.viewOwn');
INSERT INTO auth_item_child VALUES ('manager', 'user.updateOwn');
INSERT INTO auth_item_child VALUES ('manager', 'user.ownProjects');
INSERT INTO auth_item_child VALUES ('manager', 'user.ownUserGroups');
INSERT INTO auth_item_child VALUES ('manager', 'user.ownPermissions');
INSERT INTO auth_item_child VALUES ('manager', 'project.create');
INSERT INTO auth_item_child VALUES ('manager', 'project.viewOwn');
INSERT INTO auth_item_child VALUES ('manager', 'project.updateOwn');
INSERT INTO auth_item_child VALUES ('manager', 'project.ownUsers');
INSERT INTO auth_item_child VALUES ('manager', 'user-group.ownPermissions');
INSERT INTO auth_item_child VALUES ('manager', 'project-user.createOwn');
INSERT INTO auth_item_child VALUES ('manager', 'project-user.deleteOwn');
INSERT INTO auth_item_child VALUES ('chief', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('chief', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('chief', 'user.view');
INSERT INTO auth_item_child VALUES ('chief', 'user.viewAll');
INSERT INTO auth_item_child VALUES ('chief', 'user.create');
INSERT INTO auth_item_child VALUES ('chief', 'user.update');
INSERT INTO auth_item_child VALUES ('chief', 'user.projects');
INSERT INTO auth_item_child VALUES ('chief', 'user.userGroups');
INSERT INTO auth_item_child VALUES ('chief', 'user.permissions');
INSERT INTO auth_item_child VALUES ('chief', 'project.view');
INSERT INTO auth_item_child VALUES ('chief', 'project.viewAll');
INSERT INTO auth_item_child VALUES ('chief', 'project.create');
INSERT INTO auth_item_child VALUES ('chief', 'project.update');
INSERT INTO auth_item_child VALUES ('chief', 'project.users');
INSERT INTO auth_item_child VALUES ('chief', 'user-group.users');
INSERT INTO auth_item_child VALUES ('chief', 'user-group.permissions');
INSERT INTO auth_item_child VALUES ('chief', 'project-user.create');
INSERT INTO auth_item_child VALUES ('chief', 'project-user.delete');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.delete');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.deleteAll');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.view');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.viewAll');
INSERT INTO auth_item_child VALUES ('admin', 'user.view');
INSERT INTO auth_item_child VALUES ('admin', 'user.viewAll');
INSERT INTO auth_item_child VALUES ('admin', 'user.create');
INSERT INTO auth_item_child VALUES ('admin', 'user.update');
INSERT INTO auth_item_child VALUES ('admin', 'user.delete');
INSERT INTO auth_item_child VALUES ('admin', 'user.projects');
INSERT INTO auth_item_child VALUES ('admin', 'user.userGroups');
INSERT INTO auth_item_child VALUES ('admin', 'user.permissions');
INSERT INTO auth_item_child VALUES ('admin', 'project.view');
INSERT INTO auth_item_child VALUES ('admin', 'project.viewAll');
INSERT INTO auth_item_child VALUES ('admin', 'project.create');
INSERT INTO auth_item_child VALUES ('admin', 'project.update');
INSERT INTO auth_item_child VALUES ('admin', 'project.delete');
INSERT INTO auth_item_child VALUES ('admin', 'project.users');
INSERT INTO auth_item_child VALUES ('admin', 'user-group.users');
INSERT INTO auth_item_child VALUES ('admin', 'user-group.permissions');
INSERT INTO auth_item_child VALUES ('admin', 'project-user.create');
INSERT INTO auth_item_child VALUES ('admin', 'project-user.delete');


--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO auth_rule VALUES ('isOwner', 'O:18:"api\rbac\OwnerRule":3:{s:4:"name";s:7:"isOwner";s:9:"createdAt";i:1459149659;s:9:"updatedAt";i:1459149659;}', 1459149659, 1459149659);


--
-- Data for Name: d_gender; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO d_gender VALUES (1, 'male');
INSERT INTO d_gender VALUES (2, 'female');


--
-- Name: d_gender_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('d_gender_id_seq', 2, true);


--
-- Data for Name: j_user_user_group; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO j_user_user_group VALUES (3, 1);
INSERT INTO j_user_user_group VALUES (4, 3);
INSERT INTO j_user_user_group VALUES (5, 4);
INSERT INTO j_user_user_group VALUES (7, 5);
INSERT INTO j_user_user_group VALUES (8, 5);


--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO migration VALUES ('m000000_000000_base', 1458567411);
INSERT INTO migration VALUES ('m140506_102106_rbac_init', 1459145906);
INSERT INTO migration VALUES ('m160312_162824_create_d_gender_table', 1459145929);
INSERT INTO migration VALUES ('m160312_163824_create_user_table', 1459145929);
INSERT INTO migration VALUES ('m160312_180207_add_authKey_to_user', 1459145929);
INSERT INTO migration VALUES ('m160313_145005_rename_authKey_to_accessToken', 1459145929);
INSERT INTO migration VALUES ('m160313_183944_rename_camelcase_columns', 1459145929);
INSERT INTO migration VALUES ('m160313_185944_add_user_group_table', 1459145929);
INSERT INTO migration VALUES ('m160314_163921_create_access_token_table', 1459145929);
INSERT INTO migration VALUES ('m160314_184009_change_pk_access_token', 1459145929);
INSERT INTO migration VALUES ('m160314_185331_remove_access_token_column', 1459145929);
INSERT INTO migration VALUES ('m160315_190553_set_access_token_token_unique', 1459145929);
INSERT INTO migration VALUES ('m160317_172936_remove_last_login_from_user', 1459145929);
INSERT INTO migration VALUES ('m160317_173325_add_created_at_to_access_token', 1459145929);
INSERT INTO migration VALUES ('m160317_180312_set_created_at_is_not_null', 1459145929);
INSERT INTO migration VALUES ('m160318_193814_create_project_table', 1459145929);
INSERT INTO migration VALUES ('m160320_074854_rename_password_password_hash', 1459145929);
INSERT INTO migration VALUES ('m160323_185734_change_undercase_to_camelcase', 1459145929);
INSERT INTO migration VALUES ('m160323_192925_add_isActive_to_user', 1459145929);


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO project VALUES (1, 'performer', 'performer project', 1424905600, NULL, true);
INSERT INTO project VALUES (4, 'manager', 'manager project', 1424905600, NULL, true);
INSERT INTO project VALUES (5, 'performer and manager', 'performer and manager project', 1424905600, NULL, true);
INSERT INTO project VALUES (6, 'performer disabled', 'project where performer is disabled', 1424905600, NULL, true);


--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('project_id_seq', 6, true);


--
-- Data for Name: project_user; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO project_user VALUES (1, 5, 4, 1424905600, true);
INSERT INTO project_user VALUES (2, 7, 1, 1424905600, true);
INSERT INTO project_user VALUES (3, 5, 5, 1424905600, true);
INSERT INTO project_user VALUES (4, 7, 5, 1424905600, true);
INSERT INTO project_user VALUES (5, 5, 6, 1424905600, true);
INSERT INTO project_user VALUES (6, 7, 6, 1424905600, false);


--
-- Name: project_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('project_user_id_seq', 6, true);


--
-- Data for Name: project_user_record; Type: TABLE DATA; Schema: public; Owner: project_user
--



--
-- Name: project_user_record_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('project_user_record_id_seq', 1, false);


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO "user" VALUES (3, 'admin', 'admin', 'AdminLastname', 'AdminFirstname', 'AdminMiddlename', 1, 'admin@test.dev', true);
INSERT INTO "user" VALUES (4, 'chief', 'chief', 'ChiefLastname', 'ChiefFirstname', 'ChiefMiddlename', 1, 'chief@test.dev', true);
INSERT INTO "user" VALUES (5, 'manager', 'manager', 'ManagerLastname', 'ManagerFirstname', 'ManagerMiddlename', 1, 'manager@test.dev', true);
INSERT INTO "user" VALUES (7, 'performer', 'performer', 'PerformerLastname', 'PerformerFirstname', 'PerformerMiddlename', 1, 'performer@test.dev', true);
INSERT INTO "user" VALUES (8, 'disabled', 'disabled', NULL, NULL, NULL, 1, 'disabled@test.dev', false);


--
-- Data for Name: user_group; Type: TABLE DATA; Schema: public; Owner: project_user
--

INSERT INTO user_group VALUES (1, 'Admin', 'admin');
INSERT INTO user_group VALUES (3, 'Chief', 'chief');
INSERT INTO user_group VALUES (4, 'Manager', 'manager');
INSERT INTO user_group VALUES (5, 'Performer', 'performer');


--
-- Name: user_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('user_group_id_seq', 5, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: project_user
--

SELECT pg_catalog.setval('user_id_seq', 8, true);


--
-- Name: access_token_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY access_token
    ADD CONSTRAINT access_token_pkey PRIMARY KEY (id);


--
-- Name: auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: d_gender_name_key; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY d_gender
    ADD CONSTRAINT d_gender_name_key UNIQUE (name);


--
-- Name: d_gender_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY d_gender
    ADD CONSTRAINT d_gender_pkey PRIMARY KEY (id);


--
-- Name: j_user_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY j_user_user_group
    ADD CONSTRAINT j_user_user_group_pkey PRIMARY KEY ("idUser", "idUserGroup");


--
-- Name: migration_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: project_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: project_user_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY project_user
    ADD CONSTRAINT project_user_pkey PRIMARY KEY (id);


--
-- Name: project_user_record_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY project_user_record
    ADD CONSTRAINT project_user_record_pkey PRIMARY KEY (id);


--
-- Name: user_email_key; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- Name: user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_pkey PRIMARY KEY (id);


--
-- Name: user_login_key; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_login_key UNIQUE (login);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: project_user; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx-access_token-id_user; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-access_token-id_user" ON access_token USING btree ("idUser");


--
-- Name: idx-access_token-token; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE UNIQUE INDEX "idx-access_token-token" ON access_token USING btree (token);


--
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- Name: idx-j_user_user_group-id_user; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-j_user_user_group-id_user" ON j_user_user_group USING btree ("idUser");


--
-- Name: idx-j_user_user_group-id_user_group; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-j_user_user_group-id_user_group" ON j_user_user_group USING btree ("idUserGroup");


--
-- Name: idx-project_user-id_user-id_project; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE UNIQUE INDEX "idx-project_user-id_user-id_project" ON project_user USING btree ("idUser", "idProject");


--
-- Name: idx-project_user_record-id_project_user; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-project_user_record-id_project_user" ON project_user_record USING btree ("idProjectUser");


--
-- Name: idx-user-id_gender; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-user-id_gender" ON "user" USING btree ("idGender");


--
-- Name: idx-user_group-main_role; Type: INDEX; Schema: public; Owner: project_user; Tablespace: 
--

CREATE INDEX "idx-user_group-main_role" ON user_group USING btree ("mainRole");


--
-- Name: auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: fk-access_token-user; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY access_token
    ADD CONSTRAINT "fk-access_token-user" FOREIGN KEY ("idUser") REFERENCES "user"(id);


--
-- Name: fk-j_user_user_group-id_user; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY j_user_user_group
    ADD CONSTRAINT "fk-j_user_user_group-id_user" FOREIGN KEY ("idUser") REFERENCES "user"(id) ON DELETE CASCADE;


--
-- Name: fk-j_user_user_group-id_user_group; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY j_user_user_group
    ADD CONSTRAINT "fk-j_user_user_group-id_user_group" FOREIGN KEY ("idUserGroup") REFERENCES user_group(id) ON DELETE CASCADE;


--
-- Name: fk-project_user-id_project; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project_user
    ADD CONSTRAINT "fk-project_user-id_project" FOREIGN KEY ("idProject") REFERENCES project(id);


--
-- Name: fk-project_user-id_user; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project_user
    ADD CONSTRAINT "fk-project_user-id_user" FOREIGN KEY ("idUser") REFERENCES "user"(id);


--
-- Name: fk-project_user_record-id_project_user; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY project_user_record
    ADD CONSTRAINT "fk-project_user_record-id_project_user" FOREIGN KEY ("idProjectUser") REFERENCES project_user(id);


--
-- Name: fk-user-d_gender; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT "fk-user-d_gender" FOREIGN KEY ("idGender") REFERENCES d_gender(id);


--
-- Name: fk-user_group-auth_item; Type: FK CONSTRAINT; Schema: public; Owner: project_user
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT "fk-user_group-auth_item" FOREIGN KEY ("mainRole") REFERENCES auth_item(name);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

