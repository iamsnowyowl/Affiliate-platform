import React, { Component } from "react";
import {
  Button,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Card,
  CardHeader,
  CardBody
} from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import { path_assessments, baseUrl } from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import AssignAssessors from "../../views/Schedule/AssignAssessors";
import AsesiReadyAssign from "../../views/Schedule/AsesiReadyAssign";
import ButtonDelete from "../Button/ButtonDelete";

const Search = Input.Search;

type Props = {
  payloadDetail: any,
  sub_schema_number: any
};
export default class DetailAsesor extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      name: "",
      modal: false,
      modalAssign: false,
      assessment_id: "",
      hidden: true,
      message: ""
    };
  }

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
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
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

  handleSearch = searchText => {
    const { assessment_id } = this.props.params;
    var url =
      baseUrl +
      path_assessments +
      "/" +
      assessment_id +
      "/assessors?search=" +
      searchText;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + "/assessors",
      "GET"
    );
    reqwest({
      url: url,
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

  handleChange = event => {
    if (event.target.value === "") {
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = {}) => {
    const { assessment_id } = this.props.params;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + "/assessors",
      "GET"
    );
    reqwest({
      url: baseUrl + path_assessments + "/" + assessment_id + "/assessors",
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
        } else if (error.status === 419) {
          this.toggleCancel();
        }
      });
  };

  componentDidMount() {
    this.get();
  }

  handleTableChange = (pagination, filters, sorter) => {
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
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
    });
  };

  toggle = row => {
    this.setState({
      modal: !this.state.modal,
      assessor_id: row.assessor_id
    });
  };

  toggleAssign = () => {
    this.setState({
      modalAssign: !this.state.modalAssign
    });
  };

  closeModalAsesor = () => {
    this.setState({
      modalAssign: false
    });
    this.get();
  };

  closeModalAsesi = () => {
    this.setState({
      modal: false
    });
    this.get();
  };

  render() {
    const columns = [
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        render: (value, row) => {
          const full_name = row.first_name + " " + row.last_name;
          return <div>{full_name}</div>;
        }
      },
      {
        key: "contact",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.contact}
          </h5>
        ),
        dataIndex: "contact",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "assessment_assessor_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_assessor_id",
        render: (value, row) => {
          const { assessment_id } = row;
          return (
            <div>
              <Button
                className="btn btn-success"
                onClick={this.toggle.bind(this, row)}
              >
                {multiLanguage.assignAsesi}
              </Button>{" "}
              <a
                href={`${path_assessments}/${assessment_id}/assign/${row.assessor_id}`}
                className="btn btn-primary-sm"
                title={`Detail ${multiLanguage.asesi}`}
              >
                <i className="fa fa-info-circle" />
              </a>{" "}
              <ButtonDelete
                path={path_assessments + "/" + assessment_id + "/assessors"}
                id_delete={value}
              />
            </div>
          );
        }
      }
    ];
    const { assessment_id } = this.props.params;
    const { last_activity_state } = this.props.payloadDetail;
    return (
      <div className="animated fadeIn">
        {/* modal assign asesi */}
        <Modal
          isOpen={this.state.modal}
          toggle={this.toggle}
          scrollable={true}
          size="lg"
        >
          <ModalHeader toggle={this.toggle}>
            {multiLanguage.assign} {multiLanguage.asesi}
          </ModalHeader>
          <ModalBody>
            <AsesiReadyAssign
              assessment_id={assessment_id}
              payloadDetailAssessment={this.props.payloadDetail}
              assessor_id={this.state.assessor_id}
            />
          </ModalBody>
          <ModalFooter>
            <Button color="danger" onClick={this.closeModalAsesi}>
              {multiLanguage.cancel}
            </Button>
          </ModalFooter>
        </Modal>

        {/* modal assign asesor */}
        <Modal
          isOpen={this.state.modalAssign}
          toggle={this.toggleAssign}
          scrollable={true}
          size="lg"
        >
          <ModalHeader
            toggle={this.toggleAssign}
          >{`${multiLanguage.assign} ${multiLanguage.Assessors}`}</ModalHeader>
          <ModalBody>
            <AssignAssessors assessment_id={assessment_id} />
          </ModalBody>
          <ModalFooter>
            <Button color="danger" onClick={this.closeModalAsesor}>
              {multiLanguage.cancel}
            </Button>
          </ModalFooter>
        </Modal>

        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            {multiLanguage.list} {multiLanguage.assessors}
            {last_activity_state === "ADMIN_CONFIRM_FORM" ||
            last_activity_state === "ON_REVIEW_APPLICANT_DOCUMENT" ||
            last_activity_state === "ON_COMPLETED_REPORT" ? (
              <Button
                className="float-md-right"
                size="md"
                onClick={this.toggleAssign}
              >
                <i className="fa fa-plus" /> {multiLanguage.assign}{" "}
                {multiLanguage.assessors}
              </Button>
            ) : (
              ""
            )}
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
            <Table
              rowKey={record => record.assessment_assessor_id}
              columns={columns}
              dataSource={this.state.data}
              pagination={this.state.pagination}
              loading={this.state.loading}
              onChange={this.handleTableChange}
              stripe
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}
