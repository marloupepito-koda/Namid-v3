import { createRouter, createWebHistory } from "vue-router";
import Homepage from "./../homepage/Page.vue";
import About from "./../administrator/About.vue";
import Logout from "./../components/Logout.vue";
import Error404 from "./Error404.vue";
import Auth from "./../auth/Page.vue";

import AdminLayoutVue from "./../administrator/Page.vue";
import DashboardPage from "./../administrator/dashboard/Page.vue";
import Loading from "./../administrator/dashboard/components/Loading.vue";

import UnitPage from "./../administrator/dashboard/unit/Page.vue";
import UnitSectionUnitEventListPage from "./../administrator/dashboard/unit/section/unit_event_list/Page.vue";
import UnitSectionUnitTicketInventoryPage from "./../administrator/dashboard/unit/section/unit_ticket_inventory/Page.vue";
import UnitSectionUnitReturnedBagPage from "./../administrator/dashboard/unit/section/unit_return_bag/Page.vue";
import UnitLoading from "./../administrator/dashboard/unit/components/Loading.vue";
import UnitTicketsTable from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/Table.vue";
import UnitEventSection from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/sections/Event.vue";
import UnitBagSection from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/sections/Bags.vue";

import EventPage from "./../administrator/dashboard/event/Page.vue";
import EventInventoryPage from "./../administrator/dashboard/event/event_inventory/Page.vue";
import OfflineEventInventoryPage from "./../administrator/dashboard/event/event_inventory/sections/Offline.vue";
import ShowBags from "./../administrator/dashboard/event/event_inventory/sections/Bags.vue";
import EventBags from "./../administrator/dashboard/event/event_bags/Page.vue";
import EventSellers from "./../administrator/dashboard/event/event_sellers/Page.vue";
import ReturnedBagsPage from "./../administrator/dashboard/event/return/Page.vue";
import ReturnedBagsTable from "./../administrator/dashboard/event/return/components/Table.vue";
import ReturnedAllTickets from "./../administrator/dashboard/event/return/components/AllTickets.vue";
import EventHistory from "./../administrator/dashboard/event/event_history/Page.vue";
import EventLoading from "./../administrator/dashboard/event/components/Loading.vue";

import DailySellerBreakdown from "./../administrator/dashboard/event/daily_breakdown/Page.vue";

import EventLogs from "./../administrator/dashboard/event/logs/Page.vue";

import EventAllBags from "./../administrator/dashboard/event/event_bags/sections/all/Page.vue";
import EventActiveBags from "./../administrator/dashboard/event/event_bags/sections/active/Page.vue";
import EventNotActiveBags from "./../administrator/dashboard/event/event_bags/sections/not_active/Page.vue";

import TicketsInsideBag from "./../administrator/dashboard/event/inside_bag/Page.vue";
import TicketsInsideBagAll from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/Page.vue";
import TicketsInsideBagAllOffline from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/components/Offline.vue";
import TicketsInsideBagAllOffline2 from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/components/Offline2.vue";
import TicketsInsideBagSold from "./../administrator/dashboard/event/inside_bag/sections/sold_tickets/Page.vue";
import TicketsInsideBagUnsold from "./../administrator/dashboard/event/inside_bag/sections/unsold_tickets/Page.vue";
import InsideLoading from "./../administrator/dashboard/event/inside_bag/components/Loading.vue";

import TransactionLoading from "./../administrator/transaction/components/Loading.vue";
import SettlementLoading from "./../administrator/settlement/components/Loading.vue";
import AccountLoading from "./../administrator/account/components/Loading.vue";

import AccountPage from "./../administrator/account/Page.vue";
import SettlementPage from "./../administrator/settlement/Page.vue";
import TransactionPage from "./../administrator/transaction/Page.vue";

import BranchUnitPage from "./../units/unit/Page.vue";
import BranchUnitSectionUnitEventListPage from "./../units/unit/section/unit_event_list/Page.vue";
import BranchUnitSectionUnitTicketInventoryPage from "./../units/unit/section/unit_ticket_inventory/Page.vue";
import UnitTicketsTableBranch from "./../units/unit/section/unit_ticket_inventory/components/Table.vue";
import UnitEventSectionBranch from "./../units/unit/section/unit_ticket_inventory/components/sections/Event.vue";
import UnitBagSectionBranch from "./../units/unit/section/unit_ticket_inventory/components/sections/Bags.vue";
import BranchUnitSectionUnitReturnedBagPage from "./../units/unit/section/unit_return_bag/Page.vue";
import BranchUnitLoading from "./../units/unit/components/Loading.vue";

import BranchPage from "./../units/Page.vue";
import BranchEventPage from "./../units/event/Page.vue";
import BranchEventInventoryPage from "./../units/event/event_inventory/Page.vue";
import BranchOfflineEventInventoryPage from "./../units/event/event_inventory/sections/Offline.vue";
import BranchShowBags from "./../units/event/event_inventory/sections/Bags.vue";
import BranchEventBags from "./../units/event/event_bags/Page.vue";
import BranchEventSellers from "./../units/event/event_sellers/Page.vue";
import BranchEventHistory from "./../units/event/event_history/Page.vue";
import BranchEventLoading from "./../units/event/components/Loading.vue";
import BranchReturnedBagsPage from "./../units/event/return/Page.vue";
import BranchReturnedBagsTable from "./../units/event/return/components/Table.vue";
import BranchReturnedAllTickets from "./../units/event/return/components/AllTickets.vue";

import BranchEventAllBags from "./../units/event/event_bags/sections/all/Page.vue";
import BranchEventActiveBags from "./../units/event/event_bags/sections/active/Page.vue";
import BranchEventNotActiveBags from "./../units/event/event_bags/sections/not_active/Page.vue";

import BranchTicketsInsideBag from "./../units/event/inside_bag/Page.vue";
import BranchTicketsInsideBagAll from "./../units/event/inside_bag/sections/all_tickets/Page.vue";
import BranchTicketsInsideBagAllOffline from "./../units/event/inside_bag/sections/all_tickets/components/Offline.vue";
import BranchTicketsInsideBagAllOffline2 from "./../units/event/inside_bag/sections/all_tickets/components/Offline2.vue";
import BranchTicketsInsideBagSold from "./../units/event/inside_bag/sections/sold_tickets/Page.vue";
import BranchTicketsInsideBagUnsold from "./../units/event/inside_bag/sections/unsold_tickets/Page.vue";
import BranchInsideLoading from "./../units/event/inside_bag/components/Loading.vue";

const routes = [
    { path: "/:catchAll(.*)", component: Error404 },
    { path: "/", component: Homepage },
    // { path: "/about", component: About },
    { path: "/auth", component: Auth },
    { path: "/administrator/loading", component: Loading },
    { path: "/administrator/logout", component: Logout },
    {
        path: "/administrator/dashboard",
        component: AdminLayoutVue,
        children: [
            { path: "/administrator/dashboard", component: DashboardPage },
            { path: "/administrator/transaction", component: TransactionPage },
            {
                path: "/administrator/transaction/loading",
                component: TransactionLoading,
            },
            { path: "/administrator/settlement", component: SettlementPage },
            {
                path: "/administrator/settlement/loading",
                component: SettlementLoading,
            },
            { path: "/administrator/accounts", component: AccountPage },
            {
                path: "/administrator/accounts/loading",
                component: AccountLoading,
            },
            {
                path: "/administrator/dashboard",
                component: UnitPage,
                children: [
                    {
                        path: "/administrator/dashboard/:id/event_list",
                        component: UnitSectionUnitEventListPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/unit_ticket_inventory",
                        component: UnitSectionUnitTicketInventoryPage,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory",
                                component: UnitTicketsTable,
                            },
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory/events",
                                component: UnitEventSection,
                            },
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory/bags",
                                component: UnitBagSection,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/unit_returned_bag",
                        component: UnitSectionUnitReturnedBagPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/loading",
                        component: UnitLoading,
                    },
                ],
            },
            {
                path: "/administrator/dashboard/:id/:id",
                component: EventPage,
                children: [
                    {
                        path: "/administrator/dashboard/:id/:id/logs",
                        component: EventLogs,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_inventory",
                        component: EventInventoryPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_inventory/offline",
                        component: OfflineEventInventoryPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/show_bags",
                        component: ShowBags,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags",
                        component: EventBags,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/all_bags",
                                component: EventAllBags,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/active_bags",
                                component: EventActiveBags,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/not_active_bags",
                                component: EventNotActiveBags,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag",
                        component: TicketsInsideBag,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/:id",
                                component: TicketsInsideBagAll,
                            },

                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/sold_tickets/:id",
                                component: TicketsInsideBagSold,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/unsold_tickets/:id",
                                component: TicketsInsideBagUnsold,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/loading/:id",
                                component: InsideLoading,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/offline",
                        component: TicketsInsideBagAllOffline,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/offline2",
                        component: TicketsInsideBagAllOffline2,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_sellers",
                        component: EventSellers,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/returned_bags",
                        component: ReturnedBagsPage,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/returned_bags",
                                component: ReturnedBagsTable,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/returned_bags/inside_bag/all_tickets",
                                component: ReturnedAllTickets,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/daily_breakdown",
                        component: DailySellerBreakdown,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_history",
                        component: EventHistory,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/loading",
                        component: EventLoading,
                    },
                ],
            },
        ],
    },
    {
        path: "/unit/branch/logout",
        component: Logout,
    },
    {
        path: "/unit",
        component: BranchPage,
        children: [
            {
                path: "/unit/branch",
                component: BranchUnitPage,
                children: [
                    {
                        path: "/unit/branch/:id/:id/event_list",
                        component: BranchUnitSectionUnitEventListPage,
                    },
                    // {
                    //     path: "/unit/branch/:id/:id/unit_ticket_inventory",
                    //     component: BranchUnitSectionUnitTicketInventoryPage,
                    // },

                    {
                        path: "/unit/branch/:id/:id/unit_ticket_inventory",
                        component: BranchUnitSectionUnitTicketInventoryPage,
                        children: [
                            {
                                path: "/unit/branch/:id/:id/unit_ticket_inventory",
                                component: UnitTicketsTableBranch,
                            },
                            {
                                path: "/unit/branch/:id/:id/unit_ticket_inventory/events",
                                component: UnitEventSectionBranch,
                            },
                            {
                                path: "/unit/branch/:id/:id/unit_ticket_inventory/bags",
                                component: UnitBagSectionBranch,
                            },
                        ],
                    },
                    {
                        path: "/unit/branch/:id/:id/unit_returned_bag",
                        component: BranchUnitSectionUnitReturnedBagPage,
                    },
                    {
                        path: "/unit/branch/:id/:id/loading",
                        component: BranchUnitLoading,
                    },
                ],
            },
            {
                path: "/unit/branch/:id/:id/:id",
                component: BranchEventPage,
                children: [
                    {
                        path: "/unit/branch/:id/:id/:id/logs",
                        component: EventLogs,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_inventory",
                        component: BranchEventInventoryPage,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_inventory/offline",
                        component: BranchOfflineEventInventoryPage,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/show_bags",
                        component: BranchShowBags,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_bags",
                        component: BranchEventBags,
                        children: [
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/all_bags",
                                component: BranchEventAllBags,
                            },
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/active_bags",
                                component: BranchEventActiveBags,
                            },
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/not_active_bags",
                                component: BranchEventNotActiveBags,
                            },
                        ],
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_bags/inside_bag",
                        component: BranchTicketsInsideBag,
                        children: [
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/all_tickets",
                                component: BranchTicketsInsideBagAll,
                            },
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/sold_tickets",
                                component: BranchTicketsInsideBagSold,
                            },
                            {
                                path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/unsold_tickets",
                                component: BranchTicketsInsideBagUnsold,
                            },
                        ],
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/loading",
                        component: BranchInsideLoading,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/all_tickets/offline",
                        component: BranchTicketsInsideBagAllOffline,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_bags/inside_bag/all_tickets/offline2",
                        component: BranchTicketsInsideBagAllOffline2,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_sellers",
                        component: BranchEventSellers,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/returned_bags",
                        component: BranchReturnedBagsPage,
                        children: [
                            {
                                path: "/unit/branch/:id/:id/:id/returned_bags",
                                component: BranchReturnedBagsTable,
                            },
                            {
                                path: "/unit/branch/:id/:id/:id/returned_bags/inside_bag/all_tickets",
                                component: BranchReturnedAllTickets,
                            },
                        ],
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/event_history",
                        component: BranchEventHistory,
                    },
                    {
                        path: "/unit/branch/:id/:id/:id/loading",
                        component: BranchEventLoading,
                    },
                ],
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    strict: true,
});
export default router;
