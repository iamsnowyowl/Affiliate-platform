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
  Form,
  FormGroup,
  Input,
  Label,
  Alert
} from 'reactstrap';
import { Digest } from '../../containers/Helpers/digest';
import { Link } from 'react-router-dom';
import {
  baseUrl,
  path_department,
  path_faculty
} from '../../components/config/config';
import { multiLanguage } from '../Language/getBahasa';

class EditData_department extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        department_code: '',
        department_name: '',
        faculty_code: ''
      },
      editDepartment: false,
      message: '',
      hidden: true,
      payload: []
    };
  }

  componentDidMount() {
    // definition value props
    const department_code = this.props.match.params.department_code;
    const faculty_code = this.props.match.params.faculty_code;

    const authentication = Digest(
      path_faculty +
        '/' +
        faculty_code +
        path_department +
        '/' +
        department_code,
      'GET'
    );
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url:
        baseUrl +
        path_faculty +
        '/' +
        faculty_code +
        path_department +
        '/' +
        department_code
    };
    axios(options).then(request => {
      this.setState({
        payload: request.data.data
      });
    });
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleClick = event => {
    event.preventDefault();

    const department_code = this.props.match.params.department_code;
    const faculty_code = this.props.match.params.faculty_code;
    const authentication = Digest(
      '/faculties/' + faculty_code + path_department + '/' + department_code,
      'PUT'
    );
    var data = {};

    if (typeof this.state.department_code !== 'undefined')
      data['department_code'] = this.state.department_code;
    if (typeof this.state.department_name !== 'undefined')
      data['department_name'] = this.state.department_name;
    if (typeof this.state.faculty_code !== 'undefined')
      data['faculty_code'] = this.state.faculty_code;

    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        'X-Lsp-Date': authentication.date
      },
      url:
        baseUrl +
        '/faculties/' +
        faculty_code +
        path_department +
        '/' +
        department_code,
      data: data
    };
    axios(options)
      .then(response => {
        if (response.data.responseStatus === 'SUCCESS') {
          this.setState({ editDepartment: true });
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
      department_code,
      department_name,
      faculty_code
    } = this.state.payload;
    if (this.state.editDepartment) {
      return <Redirect to={'/competence/department'} />;
    }
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>Edit Data</CardHeader>
          <CardBody>
            <Form
              action=""
              encType="multipart/form-data"
              className="form-horizontal"
            >
              <FormGroup row>
                <Col md="3">
                  <Label htmlFor="department_code">
                    {multiLanguage.DepartmentCode}
                  </Label>
                </Col>
                <Col xs="12" md="9">
                  <Input
                    type="text"
                    id="department_code"
                    name="department_code"
                    placeholder={multiLanguage.DepartmentCode}
                    defaultValue={department_code}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="3">
                  <Label htmlFor="department_name">
                    {multiLanguage.DepartmentName}
                  </Label>
                </Col>
                <Col xs="12" md="9">
                  <Input
                    type="text"
                    id="department_name"
                    name="department_name"
                    placeholder={multiLanguage.DepartmentName}
                    defaultValue={department_name}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="3">
                  <Label htmlFor="faculty_code">
                    {multiLanguage.FacultyCode}
                  </Label>
                </Col>
                <Col xs="12" md="9">
                  <Input
                    type="text"
                    id="faculty_code"
                    name="faculty_code"
                    placeholder={multiLanguage.FacultyCode}
                    defaultValue={faculty_code}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
            </Form>
          </CardBody>
          <CardFooter>
            <Button
              type="submit"
              size="lg"
              color="success"
              onClick={this.handleClick}
            >
              <i className="fa fa-save" /> Save Change
            </Button>{' '}
            <Link to={'/competence/department'}>
              <Button type="submit" size="lg" color="danger">
                <i className="fa fa-ban" /> Cancel
              </Button>
              <p />
              <Alert
                className="text-center"
                color="danger"
                hidden={this.state.hidden}
              >
                {this.state.message}
              </Alert>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_department;
