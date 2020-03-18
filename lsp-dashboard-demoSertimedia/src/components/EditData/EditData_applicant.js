import React, { Component } from 'react';
import { Redirect } from 'react-router-dom';
import axios from 'axios';
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  Col,
  Label,
  Alert,
  Modal,
  ModalBody,
  ModalFooter,
  ModalHeader,
  Row,
  Input
} from 'reactstrap';
import { AvForm, AvGroup, AvInput } from 'availity-reactstrap-validation';
import { Digest } from '../../containers/Helpers/digest';
import { Link } from 'react-router-dom';
import {
  baseUrl,
  path_users,
  path_applicant,
  formatCapitalize
} from '../../components/config/config';
import '../../css/img.css';
import { multiLanguage } from '../Language/getBahasa';
import Radio from '@material-ui/core/Radio';
import RadioGroup from '@material-ui/core/RadioGroup';
import FormControlLabel from '@material-ui/core/FormControlLabel';

class EditData_applicant extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        username: '',
        email: '',
        first_name: '',
        last_name: '',
        contact: '',
        gender_code: '',
        address: '',
        role_code: '',
        picture: '',
        competence_field_code: '',
        tuk_id: ''
      },
      modal: false,
      gender: '',
      editAssessors: false,
      message: '',
      hidden: true,
      sub_input: true,
      payload: [],
      bahasa: ''
    };
  }

  toggle = () => {
    this.setState({
      modal: !this.state.modal
    });
  };

  // function GET axios
  Get(options, response) {
    axios(options).then(res => {
      this.setState({
        [response]: res.data.data,
        gender: res.data.data.gender_code
      });
    });
  }

  componentDidMount() {
    const user_id = this.props.match.params.user_id;
    const authentication = Digest(
      path_users + '/' + user_id + path_applicant,
      'GET'
    );
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url: baseUrl + path_users + '/' + user_id + path_applicant
    };

    this.Get(options, 'payload');
  }

  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
    if (event.target.name === 'gender_code') {
      this.setState({
        gender: event.target.value
      });
    }
  };

  handleChangePic = event => {
    const user_id = this.props.match.params.user_id;
    event.preventDefault();

    this.setState({
      modal: !this.state.modal
    });
    var formData = new FormData();
    var imagefile = document.querySelector('#picture');
    formData.set('picture', imagefile.files[0]);

    const authentication = Digest(
      path_users + '/' + user_id + '/picture',
      'POST'
    );
    formData.append('picture', this.state.picture);
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date,
        'Content-Type': 'multipart/form-data'
      },
      url: baseUrl + path_users + '/' + user_id + '/picture',
      data: formData
    };
    axios(options)
      .then(response => {
        if (response.status === 200) {
          window.location.reload();
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === 'ERROR') {
          this.setState({ hidden: false, message: 'Not change' });
        }
      });
  };

  handleClick = event => {
    event.preventDefault();
    this.setState({ fireRedirect: true });

    const user_id = this.props.match.params.user_id;
    var data = {};
    data['username'] = this.state.username;
    data['nik'] = this.state.nik;
    data['npwp'] = this.state.npwp;
    data['email'] = this.state.email;
    data['first_name'] = formatCapitalize(this.state.first_name);
    data['last_name'] = formatCapitalize(this.state.last_name);
    data['contact'] = formatCapitalize(this.state.contact);
    data['gender_code'] = this.state.gender;
    data['address'] = formatCapitalize(this.state.address);

    const authentication = Digest(
      path_users + '/' + user_id + path_applicant,
      'PUT'
    );
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date,
        'Content-Type': 'multipart/form-data'
      },
      url: baseUrl + path_users + '/' + user_id + path_applicant,
      data: data
    };
    axios(options)
      .then(response => {
        if (response.data.responseStatus === 'SUCCESS') {
          this.setState({ editAssessors: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === 'ERROR') {
          this.setState({ hidden: false, message: 'Not change' });
        }
      });
  };

  render() {
    const {
      username,
      email,
      first_name,
      last_name,
      contact,
      address,
      role_code,
      nik,
      npwp
    } = this.state.payload;
    if (this.state.editAssessors) {
      return <Redirect to={'/asesi'} />;
    }
    const user_id = this.props.match.params.user_id;
    return (
      <div className="animated fadeIn">
        <div>
          <Modal
            isOpen={this.state.modal}
            toggle={this.toggle}
            className={this.props.className}
          >
            <ModalHeader
              toggle={this.toggle}
            >{`${multiLanguage.Edit} ${multiLanguage.picture}`}</ModalHeader>
            <ModalBody>
              <Input
                type="file"
                id="picture"
                name="picture"
                onChange={this.handleChange}
                required
              />
            </ModalBody>
            <ModalFooter>
              <Button color="danger" onClick={this.toggle}>
                {multiLanguage.back}
              </Button>
              <Button
                type="submit"
                color="success"
                onClick={this.handleChangePic}
              >
                <i className="fa fa-check" /> {multiLanguage.submit}
              </Button>
            </ModalFooter>
          </Modal>
        </div>
        <Card>
          <CardHeader
            style={{ textAlign: 'center' }}
          >{`${multiLanguage.Edit} ${multiLanguage.asesi}`}</CardHeader>
          <CardBody>
            <AvForm
              action=""
              encType="multipart/form-data"
              className="form-horizintal"
            >
              <AvGroup row>
                <Col md="3">
                  <img
                    className="profile-picture"
                    src={baseUrl + path_users + '/' + user_id + '/picture'}
                    alt=""
                  />
                  <p />
                  <Button
                    color="success"
                    onClick={this.toggle}
                    className="change-pic"
                  >
                    {`${multiLanguage.Edit} ${multiLanguage.picture}`}
                  </Button>
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="role_code">{multiLanguage.role}</Label>
                </Col>
                <Col xs="12" md="9">
                  <AvInput
                    type="text"
                    name="role_code"
                    id="role_code"
                    value={role_code === 'APL' ? multiLanguage.asesi : ''}
                    readOnly
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="username">Username</Label>
                </Col>
                <Col xs="12" md="9">
                  <AvInput
                    type="text"
                    id="username"
                    name="username"
                    placeholder="username"
                    value={username}
                    readOnly
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="email">Email</Label>
                </Col>
                <Col xs="12" md="9">
                  <AvInput
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email"
                    value={email}
                    onChange={this.handleChange}
                    readOnly
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="NIK">NIK</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="nik"
                    name="nik"
                    placeholder="No NIK"
                    value={nik}
                    onChange={this.handleChange}
                    required
                  />
                </Col>
                <Col md="1">
                  <Label htmlFor="NPWP">NPWP</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="npwp"
                    name="npwp"
                    placeholder="No NPWP"
                    value={npwp}
                    onChange={this.handleChange}
                    required
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="first_name">{multiLanguage.firstName}</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="first_name"
                    name="first_name"
                    placeholder={multiLanguage.firstName}
                    value={first_name}
                    onChange={this.handleChange}
                  />
                </Col>
                <Col md="1">
                  <Label htmlFor="last_name">{multiLanguage.lastName}</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="last_name"
                    name="last_name"
                    value={last_name}
                    onChange={this.handleChange}
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="contact">{multiLanguage.contact}</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    style={{ borderColor: 'black' }}
                    id="contact"
                    name="contact"
                    placeholder={multiLanguage.contact}
                    value={contact}
                    onChange={this.handleChange}
                  />
                </Col>
                <Col md="1">
                  <Label>{multiLanguage.gender}</Label>
                </Col>
                <Col xs="12" md="4">
                  <RadioGroup
                    aria-label="Gender"
                    name="gender_code"
                    value={this.state.gender}
                    onChange={this.handleChange}
                  >
                    <FormControlLabel
                      value="M"
                      control={<Radio />}
                      label={multiLanguage.male}
                    />
                    <FormControlLabel
                      value="F"
                      control={<Radio />}
                      label={multiLanguage.female}
                    />
                  </RadioGroup>
                </Col>
              </AvGroup>
              {/* <AvGroup row>
                <Col md="3">
                  <Label for="TUK">TUK</Label>
                </Col>
                <Col xs="5" md="4">
                  <AvField
                    type="select"
                    name="tuk_id"
                    onChange={this.handleChange}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  >
                    <option value={tukAsesi}>{tukAsesi}</option>
                    {this.state.payloadTUK.map(
                      ({tuk_id},key)=>{
                        return (
                          <option value={tuk_id} key={tuk_id}>{tuk_id}</option>
                        );
                      }
                    )}
                  </AvField>
                </Col>
              </AvGroup> */}
              <AvGroup row>
                <Col md="3">
                  <Label htmlFor="address">{multiLanguage.address}</Label>
                </Col>
                <Col xs="12" md="9">
                  <AvInput
                    type="textarea"
                    style={{ borderColor: 'black' }}
                    id="address"
                    name="address"
                    rows="5"
                    value={address}
                    onChange={this.handleChange}
                  />
                </Col>
              </AvGroup>
            </AvForm>
            <Alert
              className="text-center"
              color="danger"
              hidden={this.state.hidden}
            >
              {this.state.message}
            </Alert>
          </CardBody>
          <CardFooter>
            <Row>
              <Col md="1.5">
                <Link to={'/asesi'}>
                  <Button type="submit" size="md" color="danger">
                    <i className="fa fa-close" /> {multiLanguage.cancel}
                  </Button>
                  <p />
                </Link>
              </Col>
              <Col md="1">
                <Button
                  type="submit"
                  size="md"
                  color="success"
                  onClick={this.handleClick}
                >
                  <i className="fa fa-save" /> {multiLanguage.submit}
                </Button>
              </Col>
            </Row>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_applicant;
