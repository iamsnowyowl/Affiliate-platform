import React, * as react from 'react';
import { Link, Redirect } from 'react-router-dom';
import Axios from 'axios';
import { Digest } from '../../containers/Helpers/digest';
import { baseUrl, path_alumni, formatCapitalize } from '../config/config';
import {
  Card,
  CardHeader,
  CardBody,
  Col,
  Label,
  Row,
  Button,
  Alert
} from 'reactstrap';
import {
  AvForm,
  AvInput,
  AvGroup,
  AvFeedback
} from 'availity-reactstrap-validation';
import { multiLanguage } from '../Language/getBahasa';

export default class InputData_alumni extends react.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        certificate_number: '',
        register_number: '',
        contact: '',
        email: '',
        assessment_date: '',
        tuk: '',
        competence: ''
      },
      payloadCompetence: [],
      place: '',
      message: '',
      inputAlumni: false,
      hidden: true
    };
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmit = event => {
    if (
      this.state.certificate_number === undefined ||
      this.state.register_number === undefined ||
      this.state.contact === undefined ||
      this.state.email === undefined ||
      this.state.competence === undefined ||
      this.state.assessment_date === undefined ||
      this.state.tuk === undefined
    ) {
      this.setState({
        hidden: true,
        message: multiLanguage.alertEmptyForm,
        inputAlumni: false
      });
    } else {
      event.preventDefault();
      this.setState({ fireRedirect: true });
      var formData = new FormData();

      const authentication = Digest(path_alumni, 'POST');
      formData.append('alumni_name', formatCapitalize(this.state.alumni_name));
      formData.append('certificate_number', this.state.certificate_number);
      formData.append('register_number', this.state.register_number);
      formData.append('contact', this.state.contact);
      formData.append('email', this.state.email);
      formData.append('assessment_date', this.state.assessment_date);
      formData.append('tuk', this.state.tuk);
      formData.append('competence', this.state.competence);

      const options = {
        method: authentication.method,
        headers: {
          Authorization: authentication.digest,
          'X-Lsp-Date': authentication.date,
          'Content-Type': 'multipart/form-data'
        },
        url: baseUrl + path_alumni,
        data: formData
      };
      Axios(options)
        .then(response => {
          if (response.data.responseStatus === 'SUCCESS') {
            this.setState({ inputAlumni: true });
          }
        })
        .catch(error => {
          let responseJSON = error.response;
          this.setState({
            response: responseJSON.data.error.code
          });

          switch (this.state.response) {
            case 400:
              this.setState({
                hidden: false,
                message: multiLanguage.alertInput
              });
              break;

            case 409:
              this.setState({
                hidden: false,
                message: multiLanguage.alertAlready
              });
              break;

            default:
              break;
          }
        });
    }
  };

  render() {
    if (this.state.inputAlumni) {
      return <Redirect to={path_alumni} />;
    }

    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader
            style={{ textAlign: 'center' }}
          >{`${multiLanguage.add} Data Alumni`}</CardHeader>
          <CardBody>
            <AvForm
              encType="multipart/form-data"
              className="form-horizontal"
              onClick={this.handleSubmit}
            >
              <AvGroup row>
                <Col md="1">
                  <Label htmlFor="certificate_number">{`No. ${multiLanguage.certificate}`}</Label>
                </Col>
                <Col xs="12" md="3">
                  <AvInput
                    type="text"
                    id="certificate_number"
                    name="certificate_number"
                    placeholder={multiLanguage.certificate}
                    onChange={this.handleChange}
                    required
                  />
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
                <Col md="1">
                  <Label htmlFor="register_number">No. Registration</Label>
                </Col>
                <Col xs="12" md="3">
                  <AvInput
                    type="text"
                    id="register_number"
                    name="register_number"
                    onChange={this.handleChange}
                    required
                  />
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
                <Col md="auto">
                  <Label htmlFor="nik">NIK</Label>
                </Col>
                <Col xs="12" md="3">
                  <AvInput
                    type="text"
                    id="nik"
                    name="nik"
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
                    placeholder={multiLanguage.schema}
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
                    placeholder="089xxxxxxxxxx"
                    onChange={this.handleChange}
                    required
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
                      <i className="fa fa-chevron-left" /> {multiLanguage.back}
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
                  >
                    <i className="fa fa-check" /> {multiLanguage.submit}
                  </Button>
                </Col>
              </Row>
            </AvForm>
          </CardBody>
        </Card>
      </div>
    );
  }
}
