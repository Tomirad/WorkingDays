# WorkingDays
	-> startDate() - return default current day or date with set: -> setDate($date) 
	-> finalDate() - return finish date with holidays day
	-> setDate(NOW, workingDay = 5) - setting process date
	-> __construct(country = 'pl', religionSaints = true) -> set JSOn src with holidays and saints day, 
                        'pl' for Poland, for other people please init: 'prv' or create own list,
                        $religionSaints - dynamic saints for catholic religions
	-> countWorkDays() - return count WorkingDays
	-> countRealDays() -> return count true days between start and end date
	-> setFormat(@format date()) -> setting format date
	-> setFreeDays(date = '01.10') -> create holiday days ex. 1st oct
	-> getSaints(country key) -> get list of free days with json file, default
