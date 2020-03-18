import React from "react";
import "antd/dist/antd.css";
import { Table, Input, InputNumber, Popconfirm, Form, Modal, Icon } from "antd";
import { AvForm, AvField, AvGroup } from "availity-reactstrap-validation";
import {
  Col,
  Card,
  CardHeader,
  CardBody,
  Button,
  Alert,
  Row
} from "reactstrap";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import LoadingOverlay from "react-loading-overlay";

import ButtonDelete from "../../../components/Button/ButtonDelete";
import {
  baseUrl,
  path_unitCompetention,
  getData,
  path_schemaViews
} from "../../../components/config/config";
import { multiLanguage } from "../../../components/Language/getBahasa";
import { Digest } from "../../../containers/Helpers/digest";
import FormMultiple from "./FormMultiple";
import LabelRequired from "../../../components/Label/LabelRequired";
import "../../../css/Button.css";

const Search = Input.Search;

const EditableContext = React.createContext();

class EditableCell extends React.Component {
  getInput = () => {
    if (this.props.inputType === "number") {
      return <InputNumber />;
    }
    return <Input />;
  };

  renderCell = ({ getFieldDecorator }) => {
    const {
      editing,
      dataIndex,
      title,
      inputType,
      record,
      index,
      children,
      ...restProps
    } = this.props;
    return (
      <td {...restProps}>
        {editing ? (
          <Form.Item style={{ margin: 0 }}>
            {getFieldDecorator(dataIndex, {
              rules: [
                {
                  required: true,
                  message: `Please Input ${title}!`
                }
              ],
              initialValue: record[dataIndex]
            })(this.getInput())}
          </Form.Item>
        ) : (
          children
        )}
      </td>
    );
  };

  render() {
    return (
      <EditableContext.Consumer>{this.renderCell}</EditableContext.Consumer>
    );
  }
}

class EditableTable extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      editingKey: "",
      visibleForm: true,
      hidden: true,
      visible: false,
      pagination: {},
      loading: false,
      modal: false,
      overlay: false,
      value: "",
      payload: [],
      unit_competence_id: "",
      unit_competence: [
        {
          unit_code: "",
          title: "",
          skkni: ""
        }
      ],
      sub_schema_number: "",
      disableButton: true
    };
    this.columns = [
      {
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            Kode Sub Skema
          </h5>
        ),
        dataIndex: "sub_schema_number",
        editable: false,
        width: "30%"
      },
      {
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.codeUnit}
          </h5>
        ),
        dataIndex: "unit_code",
        editable: true,
        width: "20%"
      },
      {
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.UnitCompetention}
          </h5>
        ),
        editable: true,
        dataIndex: "title",
        width: "30%"
      },
      {
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            SKKNI/SKKK
          </h5>
        ),
        editable: true,
        dataIndex: "skkni",
        width: "15%"
      },
      {
        dataIndex: "unit_competence_id",
        // width: "10%",
        render: (_value, record) => {
          const editable = this.isEditing(record);
          return editable ? (
            <span>
              <EditableContext.Consumer>
                {form => (
                  <a
                    // href="javascript:;"
                    className="btn btn-success col-md-auto"
                    onClick={() => this.save(form, record.unit_competence_id)}
                    style={{ marginRight: 8 }}
                    title={multiLanguage.save}
                  >
                    <i className="fa fa-check"></i>
                  </a>
                )}
              </EditableContext.Consumer>
              <Popconfirm
                title={multiLanguage.alertCancel}
                okText={multiLanguage.yes}
                cancelText={multiLanguage.cancel}
                onConfirm={() => this.cancel(record.unit_competence_id)}
              >
                <button
                  className="btn btn-danger delete-button col-md-auto"
                  title={multiLanguage.cancel}
                >
                  <i className="fa fa-close"></i>
                </button>
              </Popconfirm>
            </span>
          ) : (
            <div>
              <Button
                onClick={() => this.edit(record.unit_competence_id)}
                className="btn btn-success col-md-auto"
                title={multiLanguage.Edit}
              >
                <i className="fa fa-edit"></i>
              </Button>{" "}
              <ButtonDelete
                id_delete={record.unit_competence_id}
                path={path_unitCompetention}
              />
            </div>
          );
        }
      }
    ];
  }

  isEditing = record => record.unit_competence_id === this.state.editingKey;

  cancel = () => {
    this.setState({ editingKey: "" });
  };

  fetch = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest(path_unitCompetention, "GET");
    reqwest({
      url: baseUrl + path_unitCompetention,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = { ...this.state.pagination };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        }
      });
  };

  componentDidMount() {
    this.fetch();
    const auth = Digest("/public" + path_schemaViews, "GET");
    var link = baseUrl + "/public" + path_schemaViews + "?limit=100";

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: link
    };
    Axios(options)
      .then(response => {
        this.setState({
          visibleForm: false,
          payload: response.data.data
        });
      })
      .catch(() => {
        console.log("error");
      });
  }

  save(form, key) {
    this.setState({
      overlay: true
    });
    form.validateFields((error, row) => {
      if (error) {
        return;
      }
      const path = path_unitCompetention + "/" + key;
      var data = {};

      data["unit_code"] = row.unit_code;
      data["title"] = row.title;
      data["skkni"] = row.skkni;
      data["sub_schema_number"] = row.sub_schema_number;

      const auth = Digest(path, "PUT");
      const options = {
        method: auth.method,
        headers: {
          Authorization: auth.digest,
          "X-Lsp-Date": auth.date,
          "Content-Type": "multipart/form-data"
        },
        url: baseUrl + path,
        data: data
      };
      Axios(options).then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({
            overlay: false
          });
          this.fetch();
          this.cancel();
        }
      });
    });
  }

  edit(key) {
    this.setState({ editingKey: key });
  }

  showModal = () => {
    this.setState({
      visible: true
    });
  };

  handleOk = (_event, errors, values) => {
    this.setState({ errors, values });
    const path = path_unitCompetention;
    const auth = Digest(path, "POST");
    var formData = new FormData();

    formData.append("name", this.state.name);
    formData.append("description", this.state.description);

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path,
      data: formData
    };
    Axios(options).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({
          visible: false
        });
        this.fetch();
      }
    });
  };

  handleCancel = () => {
    this.setState({
      visible: false
    });
  };

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          search
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          reset
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleChangeModal = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest(path_unitCompetention, "GET");
    reqwest({
      url: baseUrl + path_unitCompetention + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleTableChange = (pagination, _filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }

    this.fetch({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.fetch();
    }
  };

  handleChangeForm = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
  };

  submit = (_event, errors, values) => {
    this.setState({
      errors,
      values,
      overlay: true
    });
    const { sub_schema_number, unit_competence } = this.state;
    console.log("unit competence", unit_competence[0].skkni);
    if (
      (unit_competence[0].skkni &&
        unit_competence[0].title &&
        unit_competence[0].unit_code) === ""
    ) {
      this.setState({
        overlay: false,
        hidden: false,
        messageError: multiLanguage.alertInput
      });
    } else {
      const data = {};
      // data['unit_code'] = this.state.unit_code;
      // data['title'] = this.state.title;
      // data['skkni'] = this.state.skkni;
      data["sub_schema_number"] = sub_schema_number;
      data["unit_competence"] = unit_competence;
      console.log("unit competence", data);

      Axios(getData(path_unitCompetention, "POST", data))
        .then(() => {
          window.location.reload();
        })
        .catch(error => {
          this.setState({
            overlay: false
          });
          console.log(error.response);
          if (error.response.status === 409) {
            this.setState({
              loading: false
            });
            NotificationManager.error(
              "Data Unit Kompetensi Sudah terdaftar",
              "Error",
              5000
            );
          }
        });
    }
  };

  success = () => {
    Modal.success({
      title: "SUCCESS",
      content: "Berhasil Menambahkan Unit Kompetensi"
    });
  };

  cancelModal = () => {
    this.setState({
      visible: false
    });
  };

  handleChangeSubSchema = event => {
    this.setState({
      [event.target.name]: event.target.value,
      hidden: true
    });
  };

  handleChange_UnitCompetence = event => {
    if (
      ["unit_competence_id", "unit_code", "title", "skkni"].includes(
        event.target.className
      )
    ) {
      let unit_competence = [...this.state.unit_competence];
      unit_competence[event.target.dataset.id][event.target.className] =
        event.target.value;
      if (
        (unit_competence[0].unit_code &&
          unit_competence[0].title &&
          unit_competence[0].skkni) !== ""
      ) {
        this.setState({
          disableButton: false
        });
      } else {
        this.setState({
          disableButton: true
        });
      }
      this.setState({ unit_competence });
    } else {
      this.setState({
        [event.target.unit_code]: event.target.value
      });
    }
  };

  addUnit_competence = () => {
    this.setState(prevState => ({
      unit_competence: [
        ...prevState.unit_competence,
        { unit_code: "", title: "", skkni: "" }
      ]
    }));
  };

  remove = key => {
    var unitCompetensi = this.state.unit_competence;
    unitCompetensi.splice(key, 1);
    this.setState({
      unit_competence: unitCompetensi
    });
  };

  render() {
    console.log("pagination", this.state.pagination);
    const components = {
      body: {
        cell: EditableCell
      }
    };
    const columns = this.columns.map(col => {
      if (!col.editable) {
        return col;
      }
      return {
        ...col,
        onCell: record => ({
          record,
          inputType: col.dataIndex === "age" ? "number" : "text",
          dataIndex: col.dataIndex,
          title: col.title,
          editing: this.isEditing(record)
        })
      };
    });

    const {
      unit_competence,
      pagination,
      disableButton,
      overlay,
      visible,
      payload,
      sub_schema_number,
      visibleForm,
      hidden,
      messageError,
      data
    } = this.state;
    return (
      <div className="animated fadeIn">
        <LoadingOverlay active={overlay} spinner text="Loading">
          <Modal
            title={`${multiLanguage.add}`}
            okText={`${multiLanguage.add} ${multiLanguage.UnitCompetention}`}
            visible={visible}
            onOk={this.submit}
            onCancel={this.cancelModal}
            width="50%"
            confirmLoading={overlay}
          >
            <LoadingOverlay active={overlay} spinner text="Loading">
              <AvForm>
                <AvGroup row>
                  <Col md="3">
                    <LabelRequired
                      fors="sub_schema_number"
                      label={multiLanguage.subSchemaName}
                    />
                  </Col>
                  <Col md="auto">
                    <AvField
                      className="borderInput"
                      type="select"
                      name="sub_schema_number"
                      onChange={this.handleChangeSubSchema}
                    >
                      <option value="">Pilih {multiLanguage.subSchema}</option>
                      {payload.map(item => {
                        return (
                          <option
                            key={item.sub_schema_number}
                            value={item.sub_schema_number}
                          >
                            {item.sub_schema_name}
                          </option>
                        );
                      })}
                    </AvField>
                  </Col>
                </AvGroup>
                {sub_schema_number !== "" ? (
                  <Alert color="light" hidden={visibleForm}>
                    <AvGroup onChange={this.handleChange_UnitCompetence}>
                      <FormMultiple
                        unit_competence={unit_competence}
                        remove={this.remove}
                      />
                      <p />
                      <Button
                        onClick={this.addUnit_competence}
                        color="primary"
                        hidden={disableButton}
                      >
                        {multiLanguage.add} Data
                      </Button>
                    </AvGroup>
                    {/* <AvGroup row>
                      <Col md="3">
                        <Label htmlFor="unit_code">Unit Kode</Label>
                      </Col>
                      <Col>
                        <AvInput
                          type="text"
                          id="unit_code"
                          name="unit_code"
                          placeholder="Masukan Unit Kode"
                          onChange={this.handleChangeForm}
                        />
                      </Col>
                    </AvGroup>
                    <AvGroup row>
                      <Col md="3">
                        <Label htmlFor="title">Judul Unit Kompetensi</Label>
                      </Col>
                      <Col>
                        <AvInput
                          type="text"
                          id="title"
                          name="title"
                          placeholder="Masukan Judul Unit"
                          onChange={this.handleChangeForm}
                        />
                      </Col>
                    </AvGroup>
                    <AvGroup row>
                      <Col md="3">
                        <Label htmlFor="skkni">SKKNI</Label>
                      </Col>
                      <Col>
                        <AvInput
                          type="text"
                          id="skkni"
                          name="skkni"
                          placeholder="Masukan no SKKNI"
                          onChange={this.handleChangeForm}
                        />
                      </Col>
                    </AvGroup> */}
                  </Alert>
                ) : (
                  ""
                )}
              </AvForm>
              <Alert color="danger" hidden={hidden} className="text-centered">
                {messageError}
              </Alert>
            </LoadingOverlay>
          </Modal>
          <Card>
            <CardHeader>
              <Row>
                <Col>
                  <h5
                    style={{
                      textDecoration: "underline",
                      color: "navy"
                    }}
                  >
                    Unit Kompetensi
                  </h5>
                </Col>
                <Col>
                  <Button
                    className="float-md-right"
                    color="primary"
                    onClick={this.showModal}
                  >
                    <i className="fa fa-plus" />{" "}
                    {`${multiLanguage.add} ${multiLanguage.UnitCompetention}`}
                  </Button>
                </Col>
              </Row>
            </CardHeader>
            <CardBody>
              {`${multiLanguage.searching} `}
              <Search
                placeholder={multiLanguage.search}
                onSearch={this.handleSearch}
                onChange={this.handleChange}
                style={{ width: 310 }}
              />{" "}
              <p />
              <EditableContext.Provider value={this.props.form}>
                <Table
                  components={components}
                  stripe
                  rowKey={record => record.unit_competence_id}
                  dataSource={data}
                  columns={columns}
                  rowClassName="editable-row"
                  pagination={pagination}
                  onChange={this.handleTableChange}
                />
              </EditableContext.Provider>
            </CardBody>
          </Card>
        </LoadingOverlay>
        <NotificationContainer />
      </div>
    );
  }
}

const UnitCompetention = Form.create()(EditableTable);

export default UnitCompetention;
