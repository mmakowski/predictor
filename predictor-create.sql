CREATE TABLE USERS (
  userId int(6) NOT NULL auto_increment,
  name char(12) NOT NULL,
  password char(12) NOT NULL,
  displayName varchar(50) NOT NULL,
  dateJoined TIMESTAMP NOT NULL,
  PRIMARY KEY (userId)
) TYPE=MyISAM;

CREATE TABLE GAMES (
  gameId int(4) NOT NULL auto_increment,
  name varchar(40) NOT NULL,
  dateStart DATETIME NOT NULL,
  dateEnd DATETIME NOT NULL,
  systems varchar(50),
  PRIMARY KEY (gameId)
) TYPE=MyISAM;

CREATE TABLE USER_GAMES (
  userGameId int(7) NOT NULL auto_increment,
  userId int(6) NOT NULL REFERENCES USERS,
  gameId int(4) NOT NULL REFERENCES GAMES,
  dateJoined TIMESTAMP NOT NULL,
  PRIMARY KEY (userGameId),
  UNIQUE (userId, gameId)
) TYPE=MyISAM;

CREATE TABLE MATCHES (
  matchId int(8) NOT NULL auto_increment,
  gameId int(4) NOT NULL REFERENCES GAMES,
  roundNo int(3),
  title varchar(50),
  homeTeam varchar(40) NOT NULL,
  awayTeam varchar(40) NOT NULL,
  homeGoals int(2),
  awayGoals int(2),
  neutralStadium int(1) NOT NULL DEFAULT 0,
  startPredicting DATETIME NOT NULL,
  deadline DATETIME NOT NULL,
  resultTime DATETIME,
  PRIMARY KEY (matchId)
) TYPE=MyISAM;

CREATE TABLE PREDICTION (
  predictionId int(8) NOT NULL auto_increment,
  userId int(6) NOT NULL REFERENCES USERS,
  matchId int(8) NOT NULL REFERENCES MATCHES,
  homeGoals int(2) NOT NULL,
  awayGoals int(2) NOT NULL,
  submissionTime TIMESTAMP NOT NULL,
  PRIMARY KEY (predictionId),
  UNIQUE (userId, matchId)
) TYPE=MyISAM;