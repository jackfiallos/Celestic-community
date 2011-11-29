SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `celestic` DEFAULT CHARACTER SET latin1 ;
USE `celestic` ;

-- -----------------------------------------------------
-- Table `celestic`.`stb_country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_country` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_country` (
  `country_id` INT NOT NULL AUTO_INCREMENT ,
  `country_name` VARCHAR(45) NOT NULL ,
  `country_continent` VARCHAR(45) NOT NULL ,
  `country_region` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`country_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`stb_city`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_city` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_city` (
  `city_id` INT NOT NULL AUTO_INCREMENT ,
  `city_name` VARCHAR(45) NOT NULL ,
  `city_code` VARCHAR(45) NOT NULL ,
  `city_district` VARCHAR(45) NOT NULL ,
  `city_population` VARCHAR(45) NOT NULL ,
  `country_id` INT NOT NULL ,
  PRIMARY KEY (`city_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_address`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_address` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_address` (
  `address_id` INT NOT NULL AUTO_INCREMENT ,
  `address_text` VARCHAR(100) NULL ,
  `address_postalCode` VARCHAR(6) NULL ,
  `address_phone` VARCHAR(45) NULL ,
  `address_web` VARCHAR(45) NULL ,
  `city_id` INT NULL ,
  PRIMARY KEY (`address_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_timezones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_timezones` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_timezones` (
  `timezone_id` INT NOT NULL AUTO_INCREMENT ,
  `timezone_name` VARCHAR(45) NULL ,
  PRIMARY KEY (`timezone_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `celestic`.`stb_accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_accounts` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_accounts` (
  `account_id` INT NOT NULL AUTO_INCREMENT ,
  `account_name` VARCHAR(45) NOT NULL ,
  `account_logo` VARCHAR(45) NULL DEFAULT NULL ,
  `account_colorscheme` VARCHAR(45) NULL DEFAULT NULL ,
  `account_companyName` VARCHAR(45) NULL DEFAULT NULL ,
  `account_uniqueId` VARCHAR(20) NULL DEFAULT NULL ,
  `account_projectNumbers` INT NULL DEFAULT 0 ,
  `account_storageSize` INT NULL DEFAULT 0 ,
  `address_id` INT NULL DEFAULT NULL ,
  `timezone_id` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`account_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_users` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_users` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(45) NOT NULL ,
  `user_lastname` VARCHAR(45) NOT NULL ,
  `user_email` VARCHAR(45) NOT NULL ,
  `user_phone` VARCHAR(30) NULL ,
  `user_admin` TINYINT(1)  NOT NULL ,
  `user_password` VARCHAR(32) NOT NULL ,
  `user_active` TINYINT(1)  NOT NULL ,
  `user_accountManager` TINYINT(1)  NOT NULL DEFAULT 0 ,
  `user_lastLogin` DATETIME NULL ,
  `address_id` INT NULL ,
  `account_id` INT NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_companies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_companies` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_companies` (
  `company_id` INT NOT NULL AUTO_INCREMENT ,
  `company_name` VARCHAR(100) NOT NULL ,
  `company_url` VARCHAR(100) NULL ,
  `company_uniqueId` VARCHAR(20) NULL ,
  `company_latitude` FLOAT NULL DEFAULT 19.3988 ,
  `company_longitude` FLOAT NULL DEFAULT -99.162 ,
  `address_id` INT NULL ,
  PRIMARY KEY (`company_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_companies_has_tb_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_companies_has_tb_users` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_companies_has_tb_users` (
  `company_id` INT NOT NULL ,
  `user_id` INT NOT NULL )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_currencies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_currencies` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_currencies` (
  `currency_id` INT NOT NULL AUTO_INCREMENT ,
  `currency_sign` VARCHAR(3) NOT NULL ,
  `currency_code` VARCHAR(3) NOT NULL ,
  PRIMARY KEY (`currency_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_projects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_projects` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_projects` (
  `project_id` INT NOT NULL AUTO_INCREMENT ,
  `project_name` VARCHAR(100) NOT NULL ,
  `project_description` TEXT NOT NULL ,
  `project_scope` TEXT NULL ,
  `project_restrictions` TEXT NULL ,
  `project_plataform` TEXT NULL ,
  `project_swRequirements` TEXT NULL ,
  `project_hwRequirements` TEXT NULL ,
  `project_startDate` DATE NOT NULL ,
  `project_endDate` DATE NULL ,
  `project_neverEnd` TINYINT(1)  NOT NULL DEFAULT 0 ,
  `project_active` TINYINT(1)  NOT NULL DEFAULT 1 ,
  `project_functionalReq` TEXT NULL ,
  `project_performanceReq` TEXT NULL ,
  `project_additionalComments` TEXT NULL ,
  `project_userInterfaces` TEXT NULL ,
  `project_hardwareInterfaces` TEXT NULL ,
  `project_softwareInterfaces` TEXT NULL ,
  `project_communicationInterfaces` TEXT NULL ,
  `project_backupRecovery` TEXT NULL ,
  `project_dataMigration` TEXT NULL ,
  `project_userTraining` TEXT NULL ,
  `project_installation` TEXT NULL ,
  `project_assumptions` TEXT NULL ,
  `project_outReach` TEXT NULL ,
  `project_responsibilities` TEXT NULL ,
  `project_warranty` TEXT NULL ,
  `project_additionalCosts` TEXT NULL ,
  `currency_id` INT NOT NULL ,
  `company_id` INT NOT NULL ,
  PRIMARY KEY (`project_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_status` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_status` (
  `status_id` INT NOT NULL AUTO_INCREMENT ,
  `status_name` VARCHAR(45) NOT NULL ,
  `status_order` INT NOT NULL DEFAULT 0 ,
  `status_value` INT NOT NULL DEFAULT 0 ,
  `status_required` TINYINT(1)  NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`status_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_expenses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_expenses` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_expenses` (
  `expense_id` INT NOT NULL AUTO_INCREMENT ,
  `expense_name` VARCHAR(45) NOT NULL ,
  `expense_number` VARCHAR(45) NOT NULL ,
  `expense_date` DATE NOT NULL ,
  `expense_identifier` VARCHAR(20) NULL ,
  `project_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  PRIMARY KEY (`expense_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_budgets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_budgets` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_budgets` (
  `budget_id` INT NOT NULL AUTO_INCREMENT ,
  `budget_date` DATE NOT NULL ,
  `budget_duedate` DATE NOT NULL ,
  `budget_title` VARCHAR(100) NOT NULL ,
  `budget_notes` TEXT NOT NULL ,
  `budget_token` VARCHAR(32) NULL DEFAULT NULL ,
  `project_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  `user_id` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`budget_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_diagrams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_diagrams` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_diagrams` (
  `diagram_id` INT NOT NULL AUTO_INCREMENT ,
  `diagram_name` VARCHAR(45) NOT NULL ,
  `project_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  PRIMARY KEY (`diagram_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_modules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_modules` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_modules` (
  `module_id` INT NOT NULL AUTO_INCREMENT ,
  `module_name` VARCHAR(45) NOT NULL ,
  `module_className` VARCHAR(45) NOT NULL ,
  `module_title` VARCHAR(45) NOT NULL ,
  `module_useNotifications` TINYINT(1)  NULL DEFAULT 0 ,
  `module_useSearchs` TINYINT(1)  NULL DEFAULT 0 ,
  PRIMARY KEY (`module_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_comments` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_comments` (
  `comment_id` INT NOT NULL AUTO_INCREMENT ,
  `comment_date` DATETIME NOT NULL ,
  `comment_text` TEXT NOT NULL ,
  `comment_resourceid` INT NOT NULL ,
  `module_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`comment_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_documents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_documents` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_documents` (
  `document_id` INT NOT NULL AUTO_INCREMENT ,
  `project_id` INT NOT NULL ,
  `document_name` VARCHAR(45) NOT NULL ,
  `document_description` TEXT NOT NULL ,
  `document_path` VARCHAR(255) NOT NULL ,
  `document_revision` INT NOT NULL ,
  `document_uploadDate` DATE NOT NULL ,
  `document_type` VARCHAR(45) NULL ,
  `document_baseRevision` VARCHAR(15) NULL DEFAULT 0 ,
  `comment_id` INT NOT NULL DEFAULT 0 ,
  `user_id` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`document_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_invoices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_invoices` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_invoices` (
  `invoice_id` INT NOT NULL AUTO_INCREMENT ,
  `invoice_number` VARCHAR(45) NOT NULL ,
  `invoice_date` DATE NOT NULL ,
  `project_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  `budget_id` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`invoice_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_clients`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_clients` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_clients` (
  `client_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`client_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_expensesConcepts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_expensesConcepts` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_expensesConcepts` (
  `expensesConcept_id` INT NOT NULL AUTO_INCREMENT ,
  `expensesConcept_quantity` INT NOT NULL ,
  `expensesConcept_description` TEXT NOT NULL ,
  `expensesConcept_amount` DECIMAL(10,2) NOT NULL ,
  `expense_id` INT NOT NULL ,
  PRIMARY KEY (`expensesConcept_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_budgetsConcepts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_budgetsConcepts` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_budgetsConcepts` (
  `budgetsConcept_id` INT NOT NULL AUTO_INCREMENT ,
  `budgetsConcept_quantity` INT NOT NULL ,
  `budgetsConcept_description` TEXT NOT NULL ,
  `budgetsConcept_amount` DECIMAL(10,2) NOT NULL ,
  `budget_id` INT NOT NULL ,
  PRIMARY KEY (`budgetsConcept_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_invoicesConcepts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_invoicesConcepts` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_invoicesConcepts` (
  `invoicesConcept_id` INT NOT NULL AUTO_INCREMENT ,
  `invoicesConcept_quantity` INT NOT NULL ,
  `invoicesConcept_description` TEXT NOT NULL ,
  `invoicesConcept_amount` DECIMAL(10,2) NOT NULL ,
  `invoice_id` INT NOT NULL ,
  PRIMARY KEY (`invoicesConcept_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_cases`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_cases` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_cases` (
  `case_id` INT NOT NULL AUTO_INCREMENT ,
  `case_date` DATE NOT NULL ,
  `case_code` VARCHAR(15) NOT NULL ,
  `case_name` VARCHAR(100) NOT NULL ,
  `case_actors` VARCHAR(100) NULL ,
  `case_description` TEXT NOT NULL ,
  `case_priority` TINYINT NULL DEFAULT 0 ,
  `case_requirements` TEXT NULL ,
  `project_id` INT NOT NULL ,
  `diagram_id` INT NULL ,
  `status_id` INT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`case_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_taskTypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_taskTypes` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_taskTypes` (
  `taskTypes_id` INT NOT NULL AUTO_INCREMENT ,
  `taskTypes_name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`taskTypes_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_milestones`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_milestones` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_milestones` (
  `milestone_id` INT NOT NULL AUTO_INCREMENT ,
  `milestone_title` VARCHAR(100) NOT NULL ,
  `milestone_description` TEXT NOT NULL ,
  `milestone_startdate` DATE NOT NULL ,
  `milestone_duedate` DATE NOT NULL ,
  `project_id` INT NOT NULL ,
  `user_id` INT NULL ,
  PRIMARY KEY (`milestone_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_taskStages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_taskStages` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_taskStages` (
  `taskStage_id` INT NOT NULL AUTO_INCREMENT ,
  `taskStage_name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`taskStage_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_tasks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_tasks` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_tasks` (
  `task_id` INT NOT NULL AUTO_INCREMENT ,
  `task_name` VARCHAR(100) NOT NULL ,
  `task_description` TEXT NOT NULL ,
  `task_startDate` DATE NULL DEFAULT NULL ,
  `task_endDate` DATE NULL DEFAULT NULL ,
  `task_priority` TINYINT NOT NULL ,
  `task_buildNumber` VARCHAR(20) NULL ,
  `task_position` INT NOT NULL DEFAULT 0 ,
  `status_id` INT NOT NULL ,
  `taskTypes_id` INT NOT NULL ,
  `project_id` INT NOT NULL ,
  `case_id` INT NULL ,
  `milestone_id` INT NULL ,
  `user_id` INT NOT NULL ,
  `taskStage_id` INT NOT NULL ,
  PRIMARY KEY (`task_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_users_has_tb_modules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_users_has_tb_modules` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_users_has_tb_modules` (
  `user_id` INT NOT NULL ,
  `module_id` INT NOT NULL )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_logs` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_logs` (
  `log_id` INT NOT NULL AUTO_INCREMENT ,
  `log_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `log_activity` VARCHAR(45) NOT NULL ,
  `log_resourceid` INT NOT NULL ,
  `log_type` VARCHAR(20) NOT NULL ,
  `log_commentid` INT NULL DEFAULT 0 ,
  `user_id` INT NOT NULL ,
  `module_id` INT NOT NULL ,
  `project_id` INT NULL ,
  PRIMARY KEY (`log_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_secuenceTypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_secuenceTypes` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_secuenceTypes` (
  `secuenceTypes_id` INT NOT NULL AUTO_INCREMENT ,
  `secuenceTypes_name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`secuenceTypes_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_secuences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_secuences` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_secuences` (
  `secuence_id` INT NOT NULL AUTO_INCREMENT ,
  `secuence_step` INT NOT NULL ,
  `secuence_action` VARCHAR(100) NOT NULL ,
  `secuence_responsetoAction` VARCHAR(100) NULL ,
  `case_id` INT NOT NULL ,
  `secuenceTypes_id` INT NOT NULL ,
  PRIMARY KEY (`secuence_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_validations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_validations` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_validations` (
  `validation_id` INT NOT NULL AUTO_INCREMENT ,
  `validation_field` VARCHAR(45) NOT NULL ,
  `validation_description` VARCHAR(150) NOT NULL ,
  `case_id` INT NOT NULL ,
  PRIMARY KEY (`validation_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_users_has_tb_tasks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_users_has_tb_tasks` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_users_has_tb_tasks` (
  `users_has_tasks_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NULL DEFAULT 0 ,
  `task_id` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`users_has_tasks_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_projects_has_tb_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_projects_has_tb_users` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_projects_has_tb_users` (
  `projects_has_users_id` INT NOT NULL AUTO_INCREMENT ,
  `project_id` INT NULL DEFAULT 0 ,
  `user_id` INT NULL DEFAULT 0 ,
  `isManager` TINYINT(1)  NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`projects_has_users_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`stb_authItems`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_authItems` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_authItems` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(64) NOT NULL ,
  `type` INT NOT NULL ,
  `description` TEXT NOT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  `account_id` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`stb_authItemChilds`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_authItemChilds` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_authItemChilds` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`stb_authAssignments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`stb_authAssignments` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`stb_authAssignments` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` VARCHAR(64) NOT NULL ,
  `bizrule` TEXT NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`itemname`, `userid`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_tasksDependant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_tasksDependant` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_tasksDependant` (
  `taskDependant_id` INT NOT NULL AUTO_INCREMENT ,
  `taskDependant_task_id` INT NOT NULL ,
  `task_id` INT NOT NULL ,
  PRIMARY KEY (`taskDependant_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_notifications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_notifications` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_notifications` (
  `notification_id` INT NOT NULL AUTO_INCREMENT ,
  `notification_allowed` TINYINT(1)  NOT NULL DEFAULT 0 ,
  `notification_resourceid` INT NOT NULL DEFAULT 0 ,
  `user_id` INT NOT NULL ,
  `module_id` INT NOT NULL ,
  PRIMARY KEY (`notification_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `celestic`.`tb_riskClassification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_riskClassification` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_riskClassification` (
  `riskclassification_id` INT NOT NULL AUTO_INCREMENT ,
  `riskclassification_name` VARCHAR(45) NULL ,
  PRIMARY KEY (`riskclassification_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_risks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_risks` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_risks` (
  `risk_id` INT NOT NULL AUTO_INCREMENT ,
  `risk_reportDate` DATETIME NULL ,
  `risk_description` TEXT NULL ,
  `risk_probability` TEXT NULL ,
  `risk_impact` TEXT NULL ,
  `risk_exposure` TEXT NULL ,
  `risk_firstIndicator` TEXT NULL ,
  `risk_mitigationApproaches` TEXT NULL ,
  `risk_contengencyPlan` TEXT NULL ,
  `project_id` INT NOT NULL ,
  `riskclassification_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  PRIMARY KEY (`risk_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `celestic`.`tb_todolist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_todolist` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_todolist` (
  `todolist_id` INT NOT NULL AUTO_INCREMENT ,
  `todolist_position` INT NOT NULL DEFAULT 0 ,
  `todolist_text` VARCHAR(255) NOT NULL DEFAULT '' ,
  `todolist_dtAdded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `todolist_checked` TINYINT(1)  NOT NULL DEFAULT 0 ,
  `task_id` INT NOT NULL ,
  PRIMARY KEY (`todolist_id`) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `celestic`.`tb_emails`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `celestic`.`tb_emails` ;

CREATE  TABLE IF NOT EXISTS `celestic`.`tb_emails` (
  `email_id` INT NOT NULL AUTO_INCREMENT ,
  `email_subject` VARCHAR(80) NOT NULL ,
  `email_body` TEXT NOT NULL ,
  `email_priority` INT NOT NULL ,
  `email_status` TINYINT(1)  NULL DEFAULT 0 ,
  `email_creationDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `email_sentDate` DATETIME NOT NULL ,
  `email_toName` VARCHAR(100) NOT NULL ,
  `email_toMail` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`email_id`) )
ENGINE = MyISAM;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
