import React, { Component } from "react";
import {
  Card,
  CardHeader,
  CardBody,
  Form,
  FormGroup,
  Input,
  Col,
  Label,
  Button
} from "reactstrap";
import { Redirect } from "react-router-dom";
import { Digest } from "../../containers/Helpers/digest";
import { path_assessments, path_letters, baseUrl } from "../config/config";
import Axios from "axios";

export default class EditNoSurat extends Component {
  constructor(props) {
    super(props);
    this.state = {
      assessmentId: "",
      assessmentLetterId: "",
      letter_number: "",
      NoSurat: false
    };
  }
  componentDidMount() {
    const assessment_id = this.props.match.params.assessment_id;
    const assessment_letter_id = this.props.match.params.assessment_letter_id;
    this.setState({
      assessmentId: assessment_id,
      assessmentLetterId: assessment_letter_id
    });
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmit = event => {
    event.preventDefault();
    const authentication = Digest(
      path_assessments +
        "/" +
        this.state.assessmentId +
        path_letters +
        "/" +
        this.state.assessmentLetterId,
      "PUT"
    );

    var data = {};
    data["letter_number"] = this.state.letter_number;

    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date
      },
      url:
        baseUrl +
        path_assessments +
        "/" +
        this.state.assessmentId +
        path_letters +
        "/" +
        this.state.assessmentLetterId,
      data: data
    };
    Axios(options).then(res => {
      if (res.data.responseStatus === "SUCCESS") {
        this.setState({ NoSurat: true });
      }
    });
  };

  handleBack = () => {
    const assessment_id = this.state.assessmentId;
    window.location.assign(`${path_assessments}/${assessment_id}/generate`);
  };

  render() {
    if (this.state.NoSurat) {
      return <Redirect to={path_assessments + "/list"} />;
    }
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            Input No Surat
          </CardHeader>
          <CardBody>
            <Form
              action=""
              encType="multipart/form-data"
              className="form-horizontal"
            >
              <FormGroup row>
                <Col md="1.5">
                  <Label htmlFor="no_surat">No Surat</Label>
                </Col>
                <Col xs="12" md="5">
                  <Input
                    type="text"
                    style={{ textTranform: "uppercase" }}
                    id="letter_number"
                    name="letter_number"
                    placeholder="000/LSPE/ST/X/2019"
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
              <Button
                type="submit"
                size="md"
                color="success"
                onClick={this.handleSubmit}
              >
                <i className="fa fa-save" /> Save
              </Button>{" "}
              <Button
                type="submit"
                size="md"
                color="danger"
                onClick={this.handleBack}
              >
                <i className="fa fa-chevron-left" /> Cancel
              </Button>
            </Form>
          </CardBody>
        </Card>
      </div>
    );
  }
}
