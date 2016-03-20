--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.1
-- Dumped by pg_dump version 9.5.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

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
-- Name: access_token; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE access_token (
    token character varying(256) NOT NULL,
    id_user integer NOT NULL,
    expires_in integer NOT NULL,
    id integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE access_token OWNER TO test;

--
-- Name: access_token_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE access_token_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE access_token_id_seq OWNER TO test;

--
-- Name: access_token_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE access_token_id_seq OWNED BY access_token.id;


--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE auth_assignment OWNER TO test;

--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: test
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


ALTER TABLE auth_item OWNER TO test;

--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE auth_item_child OWNER TO test;

--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE auth_rule OWNER TO test;

--
-- Name: d_gender; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE d_gender (
    id integer NOT NULL,
    name character varying(256) NOT NULL
);


ALTER TABLE d_gender OWNER TO test;

--
-- Name: d_gender_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE d_gender_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE d_gender_id_seq OWNER TO test;

--
-- Name: d_gender_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE d_gender_id_seq OWNED BY d_gender.id;


--
-- Name: j_user_user_group; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE j_user_user_group (
    id_user integer NOT NULL,
    id_user_group integer NOT NULL
);


ALTER TABLE j_user_user_group OWNER TO test;

--
-- Name: migration; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE migration OWNER TO test;

--
-- Name: project; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE project (
    id integer NOT NULL,
    name character varying(1024) NOT NULL,
    description text,
    started_at integer NOT NULL,
    ended_at integer,
    is_active boolean
);


ALTER TABLE project OWNER TO test;

--
-- Name: project_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE project_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_id_seq OWNER TO test;

--
-- Name: project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE project_id_seq OWNED BY project.id;


--
-- Name: project_user; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE project_user (
    id integer NOT NULL,
    id_user integer NOT NULL,
    id_project integer NOT NULL,
    attached_at integer NOT NULL,
    is_active boolean
);


ALTER TABLE project_user OWNER TO test;

--
-- Name: project_user_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE project_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_user_id_seq OWNER TO test;

--
-- Name: project_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE project_user_id_seq OWNED BY project_user.id;


--
-- Name: project_user_record; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE project_user_record (
    id integer NOT NULL,
    id_project_user integer NOT NULL,
    "time" integer NOT NULL
);


ALTER TABLE project_user_record OWNER TO test;

--
-- Name: project_user_record_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE project_user_record_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE project_user_record_id_seq OWNER TO test;

--
-- Name: project_user_record_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE project_user_record_id_seq OWNED BY project_user_record.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE "user" (
    id integer NOT NULL,
    login character varying(256) NOT NULL,
    lastname character varying(1024),
    firstname character varying(1024),
    middlename character varying(1024),
    id_gender integer,
    email character varying(256) NOT NULL,
    password_hash character varying(64) NOT NULL
);


ALTER TABLE "user" OWNER TO test;

--
-- Name: user_group; Type: TABLE; Schema: public; Owner: test
--

CREATE TABLE user_group (
    id integer NOT NULL,
    name character varying(1024) NOT NULL,
    main_role character varying(64) NOT NULL
);


ALTER TABLE user_group OWNER TO test;

--
-- Name: user_group_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE user_group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_group_id_seq OWNER TO test;

--
-- Name: user_group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE user_group_id_seq OWNED BY user_group.id;


--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: test
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO test;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: test
--

ALTER SEQUENCE user_id_seq OWNED BY "user".id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY access_token ALTER COLUMN id SET DEFAULT nextval('access_token_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY d_gender ALTER COLUMN id SET DEFAULT nextval('d_gender_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY project ALTER COLUMN id SET DEFAULT nextval('project_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY project_user ALTER COLUMN id SET DEFAULT nextval('project_user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY project_user_record ALTER COLUMN id SET DEFAULT nextval('project_user_record_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: test
--

ALTER TABLE ONLY user_group ALTER COLUMN id SET DEFAULT nextval('user_group_id_seq'::regclass);


--
-- Data for Name: access_token; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Name: access_token_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('access_token_id_seq', 1, false);


--
-- Data for Name: auth_assignment; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Data for Name: auth_item; Type: TABLE DATA; Schema: public; Owner: test
--

INSERT INTO auth_item VALUES ('performer', 1, 'Project performer', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('manager', 1, 'Project manager', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('chief', 1, 'Organization chief', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('admin', 1, 'Administrator', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.deleteAll', 2, 'Delete all access tokens', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.delete', 2, 'Delete access token', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.deleteOwn', 2, 'Delete own access token', 'isOwner', NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.viewAll', 2, 'View all access tokens', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.view', 2, 'View access token', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('access-token.viewOwn', 2, 'View own access token', 'isOwner', NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('user.create', 2, 'Create user', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('user.viewAll', 2, 'View all users', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('user.view', 2, 'View user', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('user.update', 2, 'Update user', NULL, NULL, 1458498016, 1458498016);
INSERT INTO auth_item VALUES ('user.delete', 2, 'Delete user', NULL, NULL, 1458498016, 1458498016);


--
-- Data for Name: auth_item_child; Type: TABLE DATA; Schema: public; Owner: test
--

INSERT INTO auth_item_child VALUES ('access-token.deleteOwn', 'access-token.delete');
INSERT INTO auth_item_child VALUES ('access-token.viewOwn', 'access-token.view');
INSERT INTO auth_item_child VALUES ('performer', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('performer', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('manager', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('manager', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('chief', 'access-token.deleteOwn');
INSERT INTO auth_item_child VALUES ('chief', 'access-token.viewOwn');
INSERT INTO auth_item_child VALUES ('chief', 'user.view');
INSERT INTO auth_item_child VALUES ('chief', 'user.viewAll');
INSERT INTO auth_item_child VALUES ('chief', 'user.create');
INSERT INTO auth_item_child VALUES ('chief', 'user.update');
INSERT INTO auth_item_child VALUES ('chief', 'user.delete');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.delete');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.deleteAll');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.view');
INSERT INTO auth_item_child VALUES ('admin', 'access-token.viewAll');
INSERT INTO auth_item_child VALUES ('admin', 'user.view');
INSERT INTO auth_item_child VALUES ('admin', 'user.viewAll');
INSERT INTO auth_item_child VALUES ('admin', 'user.create');
INSERT INTO auth_item_child VALUES ('admin', 'user.update');
INSERT INTO auth_item_child VALUES ('admin', 'user.delete');


--
-- Data for Name: auth_rule; Type: TABLE DATA; Schema: public; Owner: test
--

INSERT INTO auth_rule VALUES ('isOwner', 'O:18:"api\rbac\OwnerRule":3:{s:4:"name";s:7:"isOwner";s:9:"createdAt";i:1458498016;s:9:"updatedAt";i:1458498016;}', 1458498016, 1458498016);


--
-- Data for Name: d_gender; Type: TABLE DATA; Schema: public; Owner: test
--

INSERT INTO d_gender VALUES (1, 'male');
INSERT INTO d_gender VALUES (2, 'female');


--
-- Name: d_gender_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('d_gender_id_seq', 2, true);


--
-- Data for Name: j_user_user_group; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: test
--

INSERT INTO migration VALUES ('m000000_000000_base', 1458496768);
INSERT INTO migration VALUES ('m160312_162824_create_d_gender_table', 1458496818);
INSERT INTO migration VALUES ('m160312_163824_create_user_table', 1458496819);
INSERT INTO migration VALUES ('m160312_180207_add_authKey_to_user', 1458496819);
INSERT INTO migration VALUES ('m160313_145005_rename_authKey_to_accessToken', 1458496819);
INSERT INTO migration VALUES ('m160313_183944_rename_camelcase_columns', 1458496819);
INSERT INTO migration VALUES ('m160313_185944_add_user_group_table', 1458496819);
INSERT INTO migration VALUES ('m160314_163921_create_access_token_table', 1458496819);
INSERT INTO migration VALUES ('m160314_184009_change_pk_access_token', 1458496819);
INSERT INTO migration VALUES ('m160314_185331_remove_access_token_column', 1458496819);
INSERT INTO migration VALUES ('m160315_190553_set_access_token_token_unique', 1458496819);
INSERT INTO migration VALUES ('m160317_172936_remove_last_login_from_user', 1458496819);
INSERT INTO migration VALUES ('m160317_173325_add_created_at_to_access_token', 1458496819);
INSERT INTO migration VALUES ('m160317_180312_set_created_at_is_not_null', 1458496819);
INSERT INTO migration VALUES ('m160318_193814_create_project_table', 1458496819);
INSERT INTO migration VALUES ('m160320_074854_rename_password_password_hash', 1458496819);
INSERT INTO migration VALUES ('m140506_102106_rbac_init', 1458498010);


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('project_id_seq', 1, false);


--
-- Data for Name: project_user; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Name: project_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('project_user_id_seq', 1, false);


--
-- Data for Name: project_user_record; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Name: project_user_record_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('project_user_record_id_seq', 1, false);


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Data for Name: user_group; Type: TABLE DATA; Schema: public; Owner: test
--



--
-- Name: user_group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('user_group_id_seq', 1, false);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: test
--

SELECT pg_catalog.setval('user_id_seq', 1, false);


--
-- Name: access_token_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY access_token
    ADD CONSTRAINT access_token_pkey PRIMARY KEY (id);


--
-- Name: auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: d_gender_name_key; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY d_gender
    ADD CONSTRAINT d_gender_name_key UNIQUE (name);


--
-- Name: d_gender_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY d_gender
    ADD CONSTRAINT d_gender_pkey PRIMARY KEY (id);


--
-- Name: j_user_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY j_user_user_group
    ADD CONSTRAINT j_user_user_group_pkey PRIMARY KEY (id_user, id_user_group);


--
-- Name: migration_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: project_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: project_user_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY project_user
    ADD CONSTRAINT project_user_pkey PRIMARY KEY (id);


--
-- Name: project_user_record_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY project_user_record
    ADD CONSTRAINT project_user_record_pkey PRIMARY KEY (id);


--
-- Name: user_email_key; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- Name: user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY user_group
    ADD CONSTRAINT user_group_pkey PRIMARY KEY (id);


--
-- Name: user_login_key; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_login_key UNIQUE (login);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx-access_token-id_user; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-access_token-id_user" ON access_token USING btree (id_user);


--
-- Name: idx-access_token-token; Type: INDEX; Schema: public; Owner: test
--

CREATE UNIQUE INDEX "idx-access_token-token" ON access_token USING btree (token);


--
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- Name: idx-j_user_user_group-id_user; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-j_user_user_group-id_user" ON j_user_user_group USING btree (id_user);


--
-- Name: idx-j_user_user_group-id_user_group; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-j_user_user_group-id_user_group" ON j_user_user_group USING btree (id_user_group);


--
-- Name: idx-project_user-id_user-id_project; Type: INDEX; Schema: public; Owner: test
--

CREATE UNIQUE INDEX "idx-project_user-id_user-id_project" ON project_user USING btree (id_user, id_project);


--
-- Name: idx-project_user_record-id_project_user; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-project_user_record-id_project_user" ON project_user_record USING btree (id_project_user);


--
-- Name: idx-user-id_gender; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-user-id_gender" ON "user" USING btree (id_gender);


--
-- Name: idx-user_group-main_role; Type: INDEX; Schema: public; Owner: test
--

CREATE INDEX "idx-user_group-main_role" ON user_group USING btree (main_role);


--
-- Name: auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: test
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


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

