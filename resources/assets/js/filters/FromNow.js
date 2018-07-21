import moment from 'moment'

export default (value) => {
  moment.locale('en')
  return moment.utc(value).local().fromNow()
}
