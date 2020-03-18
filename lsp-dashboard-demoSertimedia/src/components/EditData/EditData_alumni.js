import React, { Component } from 'react';
import { Link, Redirect } from 'react-router-dom';
import {
  Row,
  Col,
  Label,
  Button,
  Alert,
  Card,
  CardHeader,
  CardBody
} from 'reactstrap';
import Axios from 'axios';
import {
  AvForm,
  AvInput,
  AvGroup,
  AvFeedback
} from 'availity-reactstrap-validation';

import { getData, path_alumni, formatCapitalize } from '../config/config';
import { multiLanguage } from '../Language/getBahasa';
import {
  // NotificationManager,
  NotificationContainer
} from 'react-notifications';
import LoadingOverlay from 'react-loading-overlay';

class EditData_alumni extends Component {
  constructor(props) {
    super(props);
    this.state = {
      payload: [],
      hidden: true,
      loading: false,
      editDataAlumni: false,
      alumni_name: '',
      competence: '',
      certificate_number: '',
      register_number: '',
      contact: '',
      email: '',
      assessment_date: '',
      tuk: ''
    };
  }

  componentDidMount = () => {
    const { alumni_id } = this.props.match.params;
    Axios(getData(path_alumni + '/' + alumni_id, 'GET')).then(response => {
      this.setState({
        payload: response.data.data
      });
    });
  };

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmit = event => {
    this.setState({
      loading: true
    });
    event.preventDefault();
    const { alumni_id } = this.props.match.params;
    const data = {};
    data['alumni_name'] = formatCapitalize(this.state.alumni_name);
    data['competence'] = this.state.competence;
    data['certificate_number'] = this.state.certificate_number;
    data['register_number'] = this.state.register_number;
    data['contact'] = this.state.contact;
    data['email'] = this.state.email;
    data['assessment_date'] = this.state.assessment_date;
    data['tuk'] = this.state.tuk;
    Axios(getData(path_alumni + '/' + alumni_id, 'PUT', data))
      .then(response => {
        this.setState({
          editDataAlumni: true
        });
      })
      .catch(error => {
        console.log('error');
      });
  };

  render() {
    const {
      alumni_name,
      competence,
      certificate_number,
      register_number,
      contact,
      email,
      assessment_date,
      tuk
    } = this.state.payload;
    if (this.state.editDataAlumni) {
      return <Redirect to={path_alumni} />;
    }
    return (
      <div className="animated fadeIn">
        <LoadingOverlay
          active={this.state.loading}
          spinner
          text="Please Wait..."
        >
          <Card>
            <CardHeader
              style={{ textAlign: 'center' }}
            >{`${multiLanguage.Edit} Data Alumni`}</CardHeader>
            <CardBody>
              <AvForm encType="multipart/form-data" className="form-horizontal">
                <AvGroup row>
                  <Col md="1">
                    <Label htmlFor="certificate_number">{`No. ${multiLanguage.certificate}`}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      id="certificate_number"
                      name="certificate_number"
                      value={certificate_number}
                      placeholder={multiLanguage.certificate}
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                  <Col md="1">
                    <Label htmlFor="register_number">No. Registration</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="text"
                      id="register_number"
                      name="register_number"
                      value={register_number}
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                  <Col md="auto">
                    <Label htmlFor="nik">NIK</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="text"
                      id="nik"
                      name="nik"
                      value="Belum Ada Di databases"
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="1">
                    <Label htmlFor="alumni_name">{multiLanguage.name}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      id="alumni_name"
                      name="alumni_name"
                      value={alumni_name}
                      placeholder={multiLanguage.name}
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: multiLanguage.alertName
                        },
                        pattern: {
                          value: '^[A-Za-z]*$',
                          errorMessage: multiLanguage.alertPatternName
                        }
                      }}
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                  <Col md="1">
                    <Label htmlFor="competence">{multiLanguage.schema}</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="text"
                      id="competence"
                      name="competence"
                      value={competence}
                      placeholder={multiLanguage.schema}
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="1">
                    <Label htmlFor="certificate_number">{`No. ${multiLanguage.certificate}`}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      id="certificate_number"
                      name="certificate_number"
                      value={certificate_number}
                      placeholder={multiLanguage.certificate}
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                  <Col md="1">
                    <Label htmlFor="register_number">No. Registration</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="text"
                      id="register_number"
                      name="register_number"
                      value={register_number}
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="1">
                    <Label htmlFor="contact">{multiLanguage.contact}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      id="contact"
                      name="contact"
                      value={contact}
                      placeholder="089xxxxxxxxxx"
                      onChange={this.handleChange}
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                  <Col md="1">
                    <Label htmlFor="email">Email</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="mail"
                      id="email"
                      name="email"
                      value={email}
                      placeholder="example@mail.com"
                      onChange={this.handleChange}
                      required
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="1">
                    <Label htmlFor="assessment_date">
                      {multiLanguage.assessmentDate}
                    </Label>
                  </Col>
                  <Col xs="5" md="3">
                    <AvInput
                      style={{ borderColor: 'black' }}
                      type="date"
                      id="assessment_date"
                      name="assessment_date"
                      value={assessment_date}
                      onChange={this.handleChange}
                    />
                  </Col>
                  <Col md="1">
                    <Label htmlFor="tuk">{multiLanguage.tukName}</Label>
                  </Col>
                  <Col xs="12" md="5">
                    <AvInput
                      type="text"
                      id="tuk"
                      name="tuk"
                      value={tuk}
                      placeholder={multiLanguage.tukName}
                      onChange={this.handleChange}
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <Alert
                  color="danger"
                  hidden={this.state.hidden}
                  className="text-center"
                >
                  {this.state.message}
                </Alert>
                <Row>
                  <Col md="1.5">
                    <Link to={path_alumni}>
                      <Button type="submit" size="md" className="btn-danger">
                        <i className="fa fa-chevron-left" />{' '}
                        {multiLanguage.back}
                      </Button>
                      <p />
                    </Link>
                  </Col>
                  <Col md="1.5">
                    <Button
                      className="btn btn-success Btn-Submit"
                      color="success"
                      size="md"
                      type="submit"
                      onClick={this.handleSubmit}
                    >
                      <i className="fa fa-check" /> {multiLanguage.submit}
                    </Button>
                  </Col>
                </Row>
              </AvForm>
            </CardBody>
          </Card>
        </LoadingOverlay>
        <NotificationContainer />
      </div>
    );
  }
}

export default EditData_alumni;
