class RaDecToAltAz{ // https://github.com/PatrissTV/RaDecToAltAz/blob/master/RaDecToAltAz.js
  constructor(ra,dec,lat,lng, DateOIn){ // Kwynn Buess added date
    this.date = DateOIn;
    this.ra = ra;
    this.dec = dec;
    this.lat = lat;
    this.lng = lng;
  }

  lst = () => {
    this.lst_hours = getLST(this.date, this.lng); // Kwynn Buess - get directly without "require"
    return this.lst_hours;
  }

  ha = () => {
    let ha = (this.hourToDeg(this.lst()) - this.hourToDeg(this.ra));
    if (ha < 0){ha = 360 + ha;}
    return ha;
  }

  hourToDeg = (value) => {
    return value*15;
  }

  getAlt = () => {
    let toRad = Math.PI/180;
    return Math.asin(Math.sin(this.dec*toRad)*Math.sin(this.lat*toRad)+Math.cos(this.dec*toRad)*Math.cos(this.lat*toRad)*Math.cos(this.ha()*toRad))/toRad;
  }

  getAz = () => {
    let toRad = Math.PI/180;
    let az = Math.acos((Math.sin(this.dec*toRad)-Math.sin(this.getAlt()*toRad)*Math.sin(this.lat*toRad))/(Math.cos(this.getAlt()*toRad)*Math.cos(this.lat*toRad)))/toRad;
    if(Math.sin(this.ha()*toRad) > 0){az = 360 - az;}
    return az;
  }
}

// module.exports = RaDecToAltAz;
