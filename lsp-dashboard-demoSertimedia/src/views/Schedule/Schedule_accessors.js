import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import moment from 'moment';
import DayPicker from 'react-day-picker';
import 'react-day-picker/lib/style.css';
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Col,
  Row,
  Button
} from 'reactstrap';
import { AvForm, AvField, AvGroup } from 'availity-reactstrap-validation';
import '../../css/mycalender.css';
import { Digest } from '../../containers/Helpers/digest';
import {
  path_accessorsSchedule,
  baseUrl
} from '../../components/config/config';
import Axios from 'axios';
import { multiLanguage } from '../../components/Language/getBahasa';

class Schedule_accessors extends Component {
  static defaultProps = {
    numberOfMonths: 2
  };

  constructor(props) {
    super(props);
    this.state = {
      list_schedule: false,
      payload: [],
      dateSelected: [],
      // monthSelect:[],
      detail: {}
    };

    this.handleChange = this.handleChange.bind(this);
  }

  componentDidMount() {
    const auth = Digest(path_accessorsSchedule, 'GET');
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        'X-Lsp-Date': auth.date,
        'Content-Type': 'application/json'
      },
      url: baseUrl + path_accessorsSchedule + '?limit=100',
      data: null
    };
    Axios(options)
      .then(response => {
        this.setState({
          payload: response.data.data
        });
      })
      .catch(err => console.log('error: '));
  }

  handleChange = event => {
    var data = event.target.value.split(',');
    var dateSelected = [];
    for (var i in data) {
      dateSelected.push(new Date(moment(data[i], 'YYYY-MM-DD')));
    }
    this.setState({
      dateSelected
    });
  };

  render() {
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: 'center' }}>
            <Row>
              <Col xs="auto">
                <h4>{multiLanguage.scheduleAsesor}</h4>
              </Col>
              <AvForm name="schedule">
                <AvGroup row>
                  <Col xs="auto">
                    <AvField
                      type="select"
                      name="accessor_id"
                      onChange={this.handleChange}
                    >
                      <option value="">{`${multiLanguage.select} ${multiLanguage.ActiveAsesor}`}</option>
                      {this.state.payload.map(
                        ({ accessor_id, first_name, CalendarDay }, key) => {
                          return (
                            <option value={CalendarDay} key={accessor_id}>
                              {first_name}
                            </option>
                          );
                        }
                      )}
                    </AvField>
                  </Col>
                </AvGroup>
              </AvForm>
            </Row>
          </CardHeader>
          <CardBody>
            <DayPicker
              numberOfMonths={12}
              month={new Date()}
              selectedDays={this.state.dateSelected}
              pagedNavigation
            />
          </CardBody>
          <CardFooter>
            <Link to={'/Assessors'}>
              <Button className="btn-danger">
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default Schedule_accessors;
