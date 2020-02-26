function ToLocalDateTime(dbDateTime)
{
    var retLocalDateTime = "";
    if (dbDateTime)
    {
        // moment date object into local date/time
        var theMoment  = moment.utc(dbDateTime).toDate();

        //format the local date/time
        retLocalDateTime = moment(theMoment).format('MM/DD/YYYY hh:mm:ss A');
    }
    return retLocalDateTime;
}
function ToLocalDate(dbDateTime)
{
    var retLocalDate = "";
    if (dbDateTime)
    {
        var theMoment  = moment.utc(dbDateTime).toDate();   // moment date object into local date/time
        retLocalDate = moment(theMoment).format('MM/DD/YYYY'); //format the local date
    }
    return retLocalDate;
}
