import React, { Component } from "react";
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
  Alert,
  Label,
  Row
} from "reactstrap";
import { Link } from "react-router-dom";
import Axios from "axios";
import { Icon, Collapse } from "antd";

import "../../css/collapse.css";
import "../../css/Button.css";

import { Digest } from "../../containers/Helpers/digest";
import {
  baseUrl,
  path_subSchema,
  path_schema,
  getData
} from "../config/config";
import { multiLanguage } from "../Language/getBahasa";
import EditData_unitCompetence from "./EditData_unitCompetence";

const { Panel } = Collapse;
const customPanelStyle = {
  background: "#f7f7f7",
  borderRadius: 4,
  marginBottom: 24,
  border: 0,
  overflow: "hidden"
};

class EditData_subSchema extends Component {
  constructor(props) {
    super(props);

    this.state = {
      loading: true,
      collapseKeys: "1",
      data: {
        sub_schema_number: "",
        sub_schema_name: "",
        schema_id: "",
        schema_name: "",
        skkni: "",
        skkk_year: ""
      },
      editSubSchema: false,
      message: "",
      hidden: true,
      payload_MainSchema: [],
      payload_SubSchema: [],
      payload_codeSubSchema: ""
    };
  }

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const { schema_id, sub_schema_id } = this.props.match.params;
    const authMainSchema = Digest(path_schema, "GET");
    const authSubSchema = Digest(
      path_schema + "/" + schema_id + path_subSchema + "/" + sub_schema_id,
      "GET"
    );

    const optionsMainSchema = {
      method: authMainSchema.method,
      headers: {
        Authorization: authMainSchema.digest,
        "X-LSP-Date": authMainSchema.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_schema + "?limit=100",
      data: null
    };
    const optionsSubSchema = {
      method: authSubSchema.method,
      headers: {
        Authorization: authSubSchema.digest,
        "X-LSP-Date": authSubSchema.date,
        "Content-Type": "application/json"
      },
      url:
        baseUrl +
        path_schema +
        "/" +
        schema_id +
        path_subSchema +
        "/" +
        sub_schema_id
    };

    this.Get(optionsMainSchema, "payload_MainSchema");
    this.Get(optionsSubSchema, "payload_SubSchema");

    Axios(
      getData(
        path_schema + "/" + schema_id + path_subSchema + "/" + sub_schema_id,
        "GET"
      )
    ).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({
          payload_codeSubSchema: response.data.data.sub_schema_number
        });
      }
    });
  }

  callbackCollapse = key => {
    const { schema_id, sub_schema_id } = this.props.match.params;
    this.setState({
      collapseKeys: key
    });
  };

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleClick = event => {
    event.preventDefault();

    const sub_schema_id = this.props.match.params.sub_schema_id;
    const schema_id = this.props.match.params.schema_id;
    var data = {};
    data["sub_schema_number"] = this.state.sub_schema_number;
    data["sub_schema_name"] = this.state.sub_schema_name;
    data["schema_id"] = this.state.schema_id;
    data["schema_name"] = this.state.schema_name;
    data["skkni"] = this.state.skkni;
    data["skkk_year"] = this.state.skkk_year;
    data["template"] = this.state.files;
    const authentication = Digest(
      path_schema + "/" + schema_id + path_subSchema + "/" + sub_schema_id,
      "PUT"
    );
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date
      },
      url:
        baseUrl +
        path_schema +
        "/" +
        schema_id +
        path_subSchema +
        "/" +
        sub_schema_id,
      data: data
    };
    Axios(options)
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          window.location.reload();
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
          this.setState({ hidden: false, message: "Not change" });
        }
      });
  };

  render() {
    const {
      payload_MainSchema,
      payload_SubSchema,
      payload_codeSubSchema
    } = this.state;
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            <h4 style={{ fontWeight: "Bold" }}>
              Edit Data {payload_SubSchema.sub_schema_name}
            </h4>
          </CardHeader>
          <CardBody>
            <Collapse
              accordion
              bordered={false}
              defaultActiveKey={["1"]}
              onChange={this.callbackCollapse}
              expandIcon={({ isActive }) => (
                <Icon type="caret-right" rotate={isActive ? 90 : 0} />
              )}
            >
              <Panel header="Data Sub Schema" key="1" style={customPanelStyle}>
                <Form
                  action=""
                  encType="multipart/form-data"
                  className="form-horizontal"
                >
                  <FormGroup row>
                    <Col md="2">
                      <Label htmlFor="schema_id">
                        {" "}
                        {multiLanguage.mainSchema}
                      </Label>
                    </Col>
                    <Col xs="12" md="3">
                      <Input
                        type="select"
                        style={{ borderColor: "black" }}
                        name="schema_id"
                        onChange={this.handleChange}
                        value={payload_SubSchema.schema_id}
                        required
                      >
                        {payload_MainSchema.map(
                          ({ schema_id, schema_name }, key) => {
                            return (
                              <option value={schema_id} key={schema_id}>
                                {" "}
                                {schema_name}
                              </option>
                            );
                          }
                        )}
                      </Input>
                    </Col>
                    <Col md="2">
                      <Label htmlFor="skkni">
                        {" "}
                        SKKNI/ SKKK / {multiLanguage.year}{" "}
                      </Label>
                    </Col>
                    <Col xs="12" md="2">
                      <Input
                        type="text"
                        style={{ borderColor: "black" }}
                        id="skkni"
                        name="skkni"
                        placeholder="skkni"
                        defaultValue={
                          payload_SubSchema.skkni === "undef"
                            ? ""
                            : payload_SubSchema.skkni
                        }
                        onChange={this.handleChange}
                      />
                    </Col>
                    <Col md="auto">/</Col>
                    <Col xs="12" md="2">
                      <Input
                        type="text"
                        style={{ borderColor: "black" }}
                        id="skkk_year"
                        name="skkk_year"
                        defaultValue={payload_SubSchema.skkk_year}
                        placeholder={multiLanguage.year}
                        onChange={this.handleChange}
                      />
                    </Col>
                  </FormGroup>
                  <FormGroup row>
                    <Col md="2">
                      <Label htmlFor="sub_schema_number">
                        {" "}
                        {multiLanguage.subSchemaCode}{" "}
                      </Label>
                    </Col>
                    <Col xs="12" md="3">
                      <Input
                        type="text"
                        style={{ borderColor: "black" }}
                        id="sub_schema_number"
                        name="sub_schema_number"
                        placeholder={multiLanguage.subSchemaCode}
                        defaultValue={payload_SubSchema.sub_schema_number}
                        onChange={this.handleChange}
                        maxLength="40"
                        required
                      />
                    </Col>
                    <Col md="2">
                      <Label htmlFor="sub_schema_name">
                        {" "}
                        {multiLanguage.subSchemaName}{" "}
                      </Label>
                    </Col>
                    <Col xs="12" md="3">
                      <Input
                        type="text"
                        style={{ borderColor: "black" }}
                        id="sub_schema_name"
                        name="sub_schema_name"
                        defaultValue={payload_SubSchema.sub_schema_name}
                        placeholder={multiLanguage.subSchemaName}
                        onChange={this.handleChange}
                      />
                    </Col>
                  </FormGroup>
                  <Row>
                    <Col md="1.5" className="Btn-Submit">
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
                  <Alert
                    color="danger"
                    hidden={this.state.hidden}
                    className="text-center"
                  >
                    {this.state.message}
                  </Alert>
                </Form>
              </Panel>
              <Panel
                header={`Unit Kompetensi`}
                key="2"
                style={customPanelStyle}
              >
                <EditData_unitCompetence
                  sub_schema_number={this.props.match.params.sub_schema_number}
                />
              </Panel>
            </Collapse>
          </CardBody>
          <CardFooter>
            <Row>
              <Col md="1.5">
                <Link to={"/schema/sub-schema"}>
                  <Button type="submit" size="md" color="danger">
                    <i className="fa fa-chevron-left" /> {multiLanguage.back}
                  </Button>
                </Link>
              </Col>
            </Row>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_subSchema;
