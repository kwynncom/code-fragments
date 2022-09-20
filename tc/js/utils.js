function getHu() {
    const uto = new Date();
    const lof = uto.getTimezoneOffset() * 60 * 1000;
    const lob = new Date(uto.getTime() - lof);
    const los = lob.toISOString();
    const nowDate = los.substring(0, 10);
    const nowTime = los.substring(11,19);
    const nowDow  = uto.toLocaleDateString([], {weekday : 'short'});
    const Ums = uto.getTime();
    const U   = parseInt(Math.round(Ums / 1000));
    
    return {'date' : nowDate, 'time' : nowTime, 'dow' : nowDow, 'Ums' : Ums, 'U' : U };
}
