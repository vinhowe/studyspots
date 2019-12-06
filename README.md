# byu-classrooms-today
Provides a list of unscheduled BYU classrooms, sorted by duration of availability.

A Jupyter Notebook scrapes room scheduling information from BYU's [free room chart](https://y.byu.edu/class_schedule/cgi/freeRoomChart.cgi), then limits the results to the rooms described by `CLASSROOM` in BYU's [room list](http://plantwo.byu.edu/download/download.php?f=space-rf.csv). I realize that this excludes some rooms which may still be useful (for example, some rooms are described as `CLASSROOM/CONFERENCE ROOM`), but it includes 244 rooms in total.
