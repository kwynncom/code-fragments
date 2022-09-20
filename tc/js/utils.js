function getHu() {
    const uto = new Date();
    const lof = uto.getTimezoneOffset() * 60 * 1000;
    const lob = new Date(uto.getTime() - lof);
    const los = lob.toISOString();
    const nowDate = los.substring(0, 10);
    const nowTime = los.substring(11,16);
    const nowDow  = uto.toLocaleDateString([], {weekday : 'short'});    
    
    return {'date' : nowDate, 'time' : nowTime, 'dow' : nowDow, 'Ums' : uto.getTime()};
}
