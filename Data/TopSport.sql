/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de creation :  4/30/2026 10:11:06 AM                    */
/*==============================================================*/
/*==============================================================*/
/* Table : Abonnement                                           */
/*==============================================================*/
create table
    Abonnement
(
    Id_Abonnement  int not null comment '',
    Id_TAbonnement int not null comment '',
    Id_Adherent    int not null comment ''
            id int not null comment '',
    DateDebut      date comment '',
    DateFin        date comment '',
    Prix           int comment '',
    primary key (Id_Abonnement)
);

/*==============================================================*/
/* Table : Activite                                             */
/*==============================================================*/
create table
    Activite
(
    Id_Activite      int not null AUTO_INCREMENT comment '',
    Id_Assurance     int not null comment '',
    id               int not null comment '',
    Res_id           int not null comment '',
    Libelle_Activite varchar(50) comment '',
    primary key (Id_Activite)
);

/*==============================================================*/
/* Table : Adherent                                             */
/*==============================================================*/
create table
    Adherent
(
    id            int not null AUTO_INCREMENT comment '',
    Res_id        int not null comment '',
    prixAssurance int comment '',
    primary key (id)
);

/*==============================================================*/
/* Table : Admin                                                */
/*==============================================================*/
create table
    Admin
(
    id           int not null comment '',
    UserName_Adm varchar(50) comment '',
    Password_Adm varchar(254) comment '',
    primary key (id)
);

/*==============================================================*/
/* Table : Assurance                                            */
/*==============================================================*/
create table
    Assurance
(
    Id_Assurance int not null AUTO_INCREMENT comment '',
    DateDebut    date comment '',
    DateFin      date comment '',
    Prix         int comment '',
    primary key (Id_Assurance)
);

/*==============================================================*/
/* Table : Entraineur                                           */
/*==============================================================*/
create table
    Entraineur
(
    id         int not null comment '',
    Adm_id     int not null comment '',
    Specialite varchar(50) comment '',
    primary key (id)
);

/*==============================================================*/
/* Table : Participer                                           */
/*==============================================================*/
create table
    Participer
(
    id          int not null comment '',
    Id_Activite int not null comment '',
    primary key (id, Id_Activite)
);

/*==============================================================*/
/* Table : Personne                                             */
/*==============================================================*/
create table
    Personne
(
    id            int not null AUTO_INCREMENT comment '',
    Nom           varchar(50) comment '',
    Prenom        varchar(50) comment '',
    Tele          varchar(50) comment '',
    DateNaissance date comment '',
    primary key (id)
);

/*==============================================================*/
/* Table : Planifier                                            */
/*==============================================================*/
create table
    Planifier
(
    Id_Activite int not null comment '',
    Id_Planning int not null comment '',
    primary key (Id_Activite, Id_Planning)
);

/*==============================================================*/
/* Table : Planing                                              */
/*==============================================================*/
create table
    Planing
(
    Id_Planning int not null AUTO_INCREMENT comment '',
    Entraineur  varchar(50) comment '',
    DateDebut   date comment '',
    DateFin     date comment '',
    jour        varchar(50),
    primary key (Id_Planning)
);

/*==============================================================*/
/* Table : Responsable                                          */
/*==============================================================*/
create table
    Responsable
(
    id         int not null comment '',
    Adm_id     int not null comment '',
    UserName   varchar(50) comment '',
    `Password` varchar(50) comment '',
    primary key (id)
);

/*==============================================================*/
/* Table : Type_Abonnement                                      */
/*==============================================================*/
create table
    Type_Abonnement
(
    Id_TAbonnement      int not null AUTO_INCREMENT comment '',
    Libelle_TAbonnement varchar(50) comment '',
    primary key (Id_TAbonnement)
);

/*==============================================================*/
/* Table : Type_Activite                                        */
/*==============================================================*/
create table
    Type_Activite
(
    Id_TActivite      int not null AUTO_INCREMENT comment '',
    Id_Activite       int not null comment '',
    Libelle_TActivite varchar(50) comment '',
    primary key (Id_TActivite)
);

alter table Abonnement
    add constraint FK_ABONNEME_APARTIENT_TYPE_ABO foreign key (Id_TAbonnement) references Type_Abonnement (Id_TAbonnement) on delete cascade on update cascade;

alter table Abonnement
    add constraint FK_ABONNEME_PROPOSER_RESPONSA foreign key (id) references Responsable (id) on delete cascade on update cascade;

ALTER TABLE Abonnement
    ADD CONSTRAINT FK_ABONNEMENT_ADHERENT FOREIGN KEY (Id_Adherent) REFERENCES Adherent (id) ON DELETE CASCADE ON UPDATE CASCADE;

alter table Activite
    add constraint FK_ACTIVITE_AFFECTER_ASSURANC foreign key (Id_Assurance) references Assurance (Id_Assurance) on delete cascade on update cascade;

alter table Activite
    add constraint FK_ACTIVITE_AFFECTER_ENTRAINE foreign key (id) references Entraineur (id) on delete cascade on update cascade;

alter table Activite
    add constraint FK_ACTIVITE_ASSOCIATI_RESPONSA foreign key (Res_id) references Responsable (id) on delete cascade on update cascade;

alter table Adherent
    add constraint FK_ADHERENT_GENERALIS_PERSONNE foreign key (id) references Personne (id) on delete cascade on update cascade;

alter table Adherent
    add constraint FK_ADHERENT_SUIVRE_RESPONSA foreign key (Res_id) references Responsable (id) on delete cascade on update cascade;

alter table Admin
    add constraint FK_ADMIN_GENERALIS_PERSONNE foreign key (id) references Personne (id) on delete cascade on update cascade;

alter table Entraineur
    add constraint FK_ENTRAINE_GENERALIS_PERSONNE foreign key (id) references Personne (id) on delete cascade on update cascade;

alter table Entraineur
    add constraint FK_ENTRAINE_SUPERVISE_ADMIN foreign key (Adm_id) references Admin (id) on delete cascade on update cascade;

alter table Participer
    add constraint FK_PARTICIP_PARTICIPE_ACTIVITE foreign key (Id_Activite) references Activite (Id_Activite) on delete cascade on update cascade;

alter table Participer
    add constraint FK_PARTICIP_PARTICIPE_ADHERENT foreign key (id) references Adherent (id) on delete cascade on update cascade;

alter table Planifier
    add constraint FK_PLANIFIE_PLANIFIER_ACTIVITE foreign key (Id_Activite) references Activite (Id_Activite) on delete cascade on update cascade;

alter table Planifier
    add constraint FK_PLANIFIE_PLANIFIER_PLANING foreign key (Id_Planning) references Planing (Id_Planning) on delete cascade on update cascade;

alter table Responsable
    add constraint FK_RESPONSA_GENERALIS_PERSONNE foreign key (id) references Personne (id) on delete cascade on update cascade;

alter table Responsable
    add constraint FK_RESPONSA_GERER_ADMIN foreign key (Adm_id) references Admin (id) on delete cascade on update cascade;

alter table Type_Activite
    add constraint FK_TYPE_ACT_CONTIENT_ACTIVITE foreign key (Id_Activite) references Activite (Id_Activite) on delete cascade on update cascade;